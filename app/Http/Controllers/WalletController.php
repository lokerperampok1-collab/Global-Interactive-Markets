<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WalletController extends Controller
{
    private const FEE_RATE = 0.00;

    /**
     * Show deposit form.
     */
    public function deposit(Request $request): View
    {
        $transactions = $request->user()->transactions()
            ->where('type', 'deposit')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('user.wallet.deposit', compact('transactions'));
    }

    /**
     * Process deposit request.
     */
    public function depositPost(Request $request): RedirectResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:10'],
        ]);

        $user = $request->user();

        $user->transactions()->create([
            'currency' => $user->wallet->currency ?? 'USD',
            'type' => 'deposit',
            'status' => 'pending',
            'amount' => $request->amount,
            'note' => 'Deposit request submitted',
        ]);

        return redirect()->route('wallet.deposit')->with('status', 'Deposit request submitted successfully. Awaiting admin approval.');
    }

    /**
     * Show withdrawal form.
     */
    public function withdraw(Request $request): View
    {
        $transactions = $request->user()->transactions()
            ->where('type', 'withdraw')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('user.wallet.withdraw', compact('transactions'));
    }

    /**
     * Process withdrawal request.
     */
    public function withdrawPost(Request $request): RedirectResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:10'],
        ]);

        $user = $request->user();

        // Rule 1: Check if withdrawal is unlocked
        if (!$user->is_withdraw_unlocked) {
            return redirect()->route('wallet.withdraw')->withErrors(['amount' => 'Your withdrawal is currently locked. Please contact support.']);
        }

        // Rule 2: Check bank details
        if (!$user->bank_name || !$user->bank_account || !$user->swift_code) {
            return redirect()->route('profile.edit')->with('status', 'Please update your bank name, account number, and SWIFT/BIC code in profile first.');
        }

        // Process withdrawal inside transaction with lock
        try {
            DB::transaction(function () use ($user, $request) {
                $wallet = $user->wallet()->lockForUpdate()->first();

                // Rule 4: Check balance
                if ($wallet->balance < $request->amount) {
                    throw new \Exception('Insufficient wallet balance.');
                }

                // Rule 5: Deduct balance immediately
                $wallet->decrement('balance', $request->amount);

                // Rule 7: Create pending withdrawal transaction
                $user->transactions()->create([
                    'currency' => $wallet->currency,
                    'type' => 'withdraw',
                    'status' => 'pending',
                    'amount' => $request->amount,
                    'note' => "Bank: {$user->bank_name} | Account: {$user->bank_account} | SWIFT: {$user->swift_code}",
                ]);
            });
        } catch (\Exception $e) {
            return redirect()->route('wallet.withdraw')->withErrors(['amount' => $e->getMessage()]);
        }

        return redirect()->route('wallet.withdraw')->with('status', 'Withdrawal request submitted successfully. Awaiting admin approval.');
    }

    /**
     * Show transfer form.
     */
    public function transfer(Request $request): View
    {
        $transactions = $request->user()->transactions()
            ->whereIn('type', ['transfer_out', 'transfer_in', 'fee'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('user.wallet.transfer', compact('transactions'));
    }

    /**
     * Process transfer request.
     */
    public function transferPost(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $sender = $request->user();

        // Rule 1: Cannot transfer to self
        if ($sender->email === $request->email) {
            return redirect()->route('wallet.transfer')->withErrors(['email' => 'You cannot transfer funds to yourself.']);
        }

        // Rule 2: Recipient must exist
        $recipient = User::where('email', $request->email)->first();
        if (!$recipient) {
            return redirect()->route('wallet.transfer')->withErrors(['email' => 'Recipient user not found.']);
        }

        $amount = $request->amount;
        $fee = $amount * self::FEE_RATE;
        $totalDeduction = $amount + $fee;

        try {
            DB::transaction(function () use ($sender, $recipient, $amount, $fee, $totalDeduction) {
                 // Lock both wallets in sorted order of user_ids to prevent deadlocks
                 $userIds = [$sender->id, $recipient->id];
                 sort($userIds);

                 $wallets = \App\Models\Wallet::whereIn('user_id', $userIds)
                     ->lockForUpdate()
                     ->get()
                     ->keyBy('user_id');

                 $senderWallet = $wallets->get($sender->id);
                 $recipientWallet = $wallets->get($recipient->id);

                 if (!$senderWallet || !$recipientWallet) {
                     throw new \Exception('Wallet not found.');
                 }

                // Rule 4: Check sender balance
                if ($senderWallet->balance < $totalDeduction) {
                    throw new \Exception('Insufficient wallet balance.');
                }

                // Deduct sender balance (amount + fee)
                $senderWallet->decrement('balance', $totalDeduction);

                // Add recipient balance (amount)
                $recipientWallet->increment('balance', $amount);

                // Create transaction records
                // Sender transaction (transfer_out)
                $sender->transactions()->create([
                    'currency' => $senderWallet->currency,
                    'type' => 'transfer_out',
                    'status' => 'approved',
                    'amount' => $amount,
                    'note' => "Transferred to: {$recipient->email}",
                ]);

                // Recipient transaction (transfer_in)
                $recipient->transactions()->create([
                    'currency' => $recipientWallet->currency,
                    'type' => 'transfer_in',
                    'status' => 'approved',
                    'amount' => $amount,
                    'note' => "Transferred from: {$sender->email}",
                ]);

                // Fee transaction (fee) - only if greater than 0
                if ($fee > 0) {
                    $sender->transactions()->create([
                        'currency' => $senderWallet->currency,
                        'type' => 'fee',
                        'status' => 'approved',
                        'amount' => $fee,
                        'note' => "Transfer fee for sending to: {$recipient->email}",
                    ]);
                }
            });
        } catch (\Exception $e) {
            return redirect()->route('wallet.transfer')->withErrors(['amount' => $e->getMessage()]);
        }

        return redirect()->route('wallet.transfer')->with('status', 'Funds transferred successfully.');
    }
}
