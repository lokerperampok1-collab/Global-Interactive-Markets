<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InvestmentPlan;
use App\Models\UserInvestment;
use App\Traits\AutoMaturity;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InvestmentController extends Controller
{
    use AutoMaturity;

    /**
     * Display the investment catalog or active investments.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // 1. Auto-create wallet if it doesn't exist
        if (!$user->wallet) {
            $user->wallet()->create([
                'currency' => $user->currency_code ?? 'USD',
                'balance' => 0.00,
            ]);
        }

        // 2. Auto-maturity check
        $maturityResult = $this->checkMaturity($user);
        if ($maturityResult['count'] > 0) {
            $request->session()->flash('status', "Congratulations! {$maturityResult['count']} investment(s) have matured and \${$maturityResult['total']} has been credited to your wallet.");
        }

        // 3. Check for active investments
        $activeInvestments = UserInvestment::where('user_id', $user->id)
            ->where('status', 'active')
            ->orderBy('end_at', 'asc')
            ->get();

        if ($activeInvestments->isNotEmpty()) {
            return view('user.investment_active', compact('activeInvestments'));
        }

        // 4. Show catalog of active plans
        $plans = InvestmentPlan::where('status', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('user.investment', compact('plans'));
    }

    /**
     * Display investment history (all investments: active, matured, cancelled).
     */
    public function history(Request $request): View
    {
        $user = $request->user();

        // Auto-maturity check
        $this->checkMaturity($user);

        $investments = UserInvestment::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Summary stats
        $totalInvested = UserInvestment::where('user_id', $user->id)->sum('amount');
        $totalReturn = UserInvestment::where('user_id', $user->id)->where('status', 'completed')->sum('target_return');
        $activeCount = UserInvestment::where('user_id', $user->id)->where('status', 'active')->count();
        $maturedCount = UserInvestment::where('user_id', $user->id)->where('status', 'completed')->count();

        return view('user.investment_history', compact('investments', 'totalInvested', 'totalReturn', 'activeCount', 'maturedCount'));
    }

    /**
     * Process investment purchase.
     */
    public function invest(Request $request): RedirectResponse
    {
        $request->validate([
            'plan_id' => ['required', 'exists:investment_plans,id'],
        ]);

        $user = $request->user();
        $plan = InvestmentPlan::findOrFail($request->plan_id);

        if (!$plan->status) {
            return redirect()->route('investment.index')->withErrors(['plan_id' => 'This investment plan is currently unavailable.']);
        }

        try {
            DB::transaction(function () use ($user, $plan) {
                $wallet = $user->wallet()->lockForUpdate()->first();

                if (!$wallet) {
                    throw new \Exception('Wallet not initialized.');
                }

                // Check balance
                if ($wallet->balance < $plan->price) {
                    throw new \Exception('Insufficient wallet balance to purchase this plan.');
                }

                // Deduct balance
                $wallet->decrement('balance', $plan->price);

                // Create investment transaction
                $user->transactions()->create([
                    'currency' => $wallet->currency,
                    'type' => 'investment',
                    'status' => 'approved',
                    'amount' => $plan->price,
                    'note' => "Invested in plan: {$plan->name}",
                ]);

                // Create user investment with random maturity between 3-6 hours
                $randomMinutes = rand(180, 360); // 3 hours (180 min) to 6 hours (360 min)

                UserInvestment::create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->name,
                    'amount' => $plan->price,
                    'target_return' => $plan->target_return,
                    'duration_days' => (int) round($randomMinutes / 60),
                    'start_at' => now(),
                    'end_at' => now()->addMinutes($randomMinutes),
                    'status' => 'active',
                ]);
            });
        } catch (\Exception $e) {
            return redirect()->route('investment.index')->withErrors(['plan_id' => $e->getMessage()]);
        }

        return redirect()->route('investment.index')->with('status', "Invested in {$plan->name} successfully. Your investment is now active.");
    }
}
