<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\KycRequest;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display admin stats overview.
     */
    public function index(): View
    {
        $totalUsers = User::where('role', 'user')->count();
        $pendingKyc = User::where('status_kyc', 'pending')->count();
        $pendingTx = WalletTransaction::where('status', 'pending')->count();

        return view('admin.dashboard', compact('totalUsers', 'pendingKyc', 'pendingTx'));
    }

    /**
     * List all users.
     */
    public function users(): View
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users', compact('users'));
    }

    /**
     * Show edit user form.
     */
    public function editUser($id): View
    {
        $user = User::findOrFail($id);
        return view('admin.users_edit', compact('user'));
    }

    /**
     * Update user profile by admin.
     */
    public function updateUser(Request $request, $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['required', 'string', 'max:20'],
            'role' => ['required', 'in:admin,user'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'bank_account' => ['nullable', 'string', 'max:50'],
            'swift_code' => ['nullable', 'string', 'max:20'],
            'status_kyc' => ['required', 'in:none,pending,approved,rejected'],
        ]);

        $user->update($request->only([
            'name', 'email', 'phone', 'role',
            'bank_name', 'bank_account', 'swift_code', 'status_kyc'
        ]));

        return redirect()->route('admin.users')->with('status', 'User profile updated successfully.');
    }

    /**
     * Adjust user balance by admin.
     */
    public function adjustBalance(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric'],
        ]);

        $user = User::findOrFail($id);
        $amount = $request->amount;

        try {
            DB::transaction(function () use ($user, $amount) {
                $wallet = $user->wallet()->lockForUpdate()->first();
                if (!$wallet) {
                    $wallet = $user->wallet()->create([
                        'currency' => $user->currency_code ?? 'USD',
                        'balance' => 0.00,
                    ]);
                }

                if ($amount < 0) {
                    $absAmount = abs($amount);
                    if ($wallet->balance < $absAmount) {
                        $sym = $user->currency_symbol ?? '$';
                        throw new \Exception("Cannot deduct {$sym}{$absAmount} because user's current balance is only {$sym}{$wallet->balance}.");
                    }
                }

                $wallet->balance += $amount;
                $wallet->save();

                $user->transactions()->create([
                    'currency' => $wallet->currency,
                    'type' => 'profit',
                    'status' => 'approved',
                    'amount' => $amount,
                    'note' => $amount > 0 ? 'Admin adjustment (Credit)' : 'Admin adjustment (Debit)',
                ]);
            });
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['amount' => $e->getMessage()]);
        }

        return redirect()->back()->with('status', 'Wallet balance adjusted successfully.');
    }

    /**
     * Reset user password by admin.
     */
    public function resetPassword($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make('12345678')
        ]);

        return redirect()->back()->with('status', 'Password reset to 12345678 successfully.');
    }

    /**
     * Toggle withdrawal status.
     */
    public function toggleWithdraw($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->update([
            'is_withdraw_unlocked' => !$user->is_withdraw_unlocked
        ]);

        $statusStr = $user->is_withdraw_unlocked ? 'unlocked' : 'locked';
        return redirect()->back()->with('status', "User withdrawal capability has been {$statusStr}.");
    }

    /**
     * Delete a user account.
     */
    public function deleteUser($id): RedirectResponse
    {
        $admin = Auth::user();
        if ($admin->id == $id) {
            return redirect()->back()->withErrors(['delete' => 'You cannot delete your own admin account.']);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('status', "User account {$user->name} has been deleted successfully.");
    }

    /**
     * Impersonate a user.
     */
    public function impersonate(Request $request, $id): RedirectResponse
    {
        $admin = Auth::user();
        if ($admin->id == $id) {
            return redirect()->back()->withErrors(['impersonate' => 'You cannot impersonate yourself.']);
        }

        $userToImpersonate = User::findOrFail($id);

        $request->session()->put('impersonate', $admin->id);
        Auth::login($userToImpersonate);

        return redirect()->route('dashboard')->with('status', "Now impersonating {$userToImpersonate->name}.");
    }

    /**
     * Leave impersonation and return to admin.
     */
    public function leaveImpersonate(Request $request): RedirectResponse
    {
        if (!$request->session()->has('impersonate')) {
            return redirect()->route('dashboard');
        }

        $adminId = $request->session()->pull('impersonate');
        $admin = User::findOrFail($adminId);
        Auth::login($admin);

        return redirect()->route('admin.users')->with('status', 'Returned to admin session.');
    }

    /**
     * List pending KYC requests.
     */
    public function kyc(): View
    {
        $kycRequests = KycRequest::where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
        return view('admin.kyc', compact('kycRequests'));
    }

    /**
     * Approve KYC request.
     */
    public function approveKyc($id): RedirectResponse
    {
        $kyc = KycRequest::findOrFail($id);
        
        DB::transaction(function () use ($kyc) {
            $kyc->update(['status' => 'approved']);
            $kyc->user->update(['status_kyc' => 'approved']);
        });

        return redirect()->route('admin.kyc')->with('status', 'KYC approved successfully.');
    }

    /**
     * Reject KYC request.
     */
    public function rejectKyc(Request $request, $id): RedirectResponse
    {
        $kyc = KycRequest::findOrFail($id);

        DB::transaction(function () use ($kyc, $request) {
            $kyc->update([
                'status' => 'rejected',
                'note' => $request->note ?? 'Documents rejected by admin.',
            ]);
            $kyc->user->update(['status_kyc' => 'rejected']);
        });

        return redirect()->route('admin.kyc')->with('status', 'KYC rejected successfully.');
    }

    /**
     * List pending transactions.
     */
    public function wallet(): View
    {
        $transactions = WalletTransaction::where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
        return view('admin.wallet', compact('transactions'));
    }

    /**
     * Approve pending transaction.
     */
    public function approveTx($id): RedirectResponse
    {
        $tx = WalletTransaction::findOrFail($id);

        try {
            DB::transaction(function () use ($tx) {
                $tx->update(['status' => 'approved']);

                if ($tx->type === 'deposit') {
                    $wallet = $tx->user->wallet()->lockForUpdate()->first();
                    if (!$wallet) {
                        $wallet = $tx->user->wallet()->create([
                            'currency' => $tx->user->currency_code ?? 'USD',
                            'balance' => 0.00,
                        ]);
                    }
                    $wallet->increment('balance', $tx->amount);
                }
                // Withdraw balance is already deducted when user creates the request, so nothing to deduct on approve
            });
        } catch (\Exception $e) {
            return redirect()->route('admin.wallet')->withErrors(['wallet' => $e->getMessage()]);
        }

        return redirect()->route('admin.wallet')->with('status', 'Transaction approved successfully.');
    }

    /**
     * Reject pending transaction.
     */
    public function rejectTx($id): RedirectResponse
    {
        $tx = WalletTransaction::findOrFail($id);

        try {
            DB::transaction(function () use ($tx) {
                $tx->update(['status' => 'rejected']);

                if ($tx->type === 'withdraw') {
                    // Refund balance to wallet
                    $wallet = $tx->user->wallet()->lockForUpdate()->first();
                    $wallet->increment('balance', $tx->amount);
                }
            });
        } catch (\Exception $e) {
            return redirect()->route('admin.wallet')->withErrors(['wallet' => $e->getMessage()]);
        }

        return redirect()->route('admin.wallet')->with('status', 'Transaction rejected successfully.');
    }
}
