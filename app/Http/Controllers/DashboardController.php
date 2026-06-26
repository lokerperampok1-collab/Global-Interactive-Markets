<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\AutoMaturity;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    use AutoMaturity;

    public function index(Request $request): View
    {
        $user = $request->user();

        // 1. Auto-create wallet if it doesn't exist
        $wallet = $user->wallet;
        if (!$wallet) {
            $wallet = $user->wallet()->create([
                'currency' => $user->currency_code ?? 'USD',
                'balance' => 0.00,
            ]);
        }

        // 2. Auto-maturity check
        $maturityResult = $this->checkMaturity($user);
        if ($maturityResult['count'] > 0) {
            $request->session()->flash('status', "Congratulations! {$maturityResult['count']} investment(s) have matured and \${$maturityResult['total']} has been credited to your wallet.");
        }

        // 3. Calculate financial metrics
        $balance = $wallet->balance;

        $totalProfit = $user->transactions()
            ->where('type', 'profit')
            ->where('status', 'approved')
            ->sum('amount');

        $totalDeposit = $user->transactions()
            ->where('type', 'deposit')
            ->where('status', 'approved')
            ->sum('amount');

        $totalWithdrawal = $user->transactions()
            ->where('type', 'withdraw')
            ->where('status', 'approved')
            ->sum('amount');

        $totalInvestment = $user->transactions()
            ->where('type', 'investment')
            ->where('status', 'approved')
            ->sum('amount');

        $activeInvestment = $user->investments()
            ->where('status', 'active')
            ->sum('amount');

        // 4. TradingView Random Symbol
        $symbols = [
            ['name' => 'BTC/USD', 'tv_symbol' => 'COINBASE:BTCUSD'],
            ['name' => 'ETH/USD', 'tv_symbol' => 'COINBASE:ETHUSD'],
            ['name' => 'EUR/USD', 'tv_symbol' => 'FX:EURUSD'],
            ['name' => 'USD/JPY', 'tv_symbol' => 'FX:USDJPY'],
            ['name' => 'Gold', 'tv_symbol' => 'TVC:GOLD'],
            ['name' => 'Silver', 'tv_symbol' => 'TVC:SILVER'],
            ['name' => 'Crude Oil', 'tv_symbol' => 'TVC:USOIL'],
        ];
        $selected = $symbols[array_rand($symbols)];
        $symbolName = $selected['name'];
        $tvSymbol = $selected['tv_symbol'];

        return view('dashboard', compact(
            'balance',
            'totalProfit',
            'totalDeposit',
            'totalWithdrawal',
            'totalInvestment',
            'activeInvestment',
            'symbolName',
            'tvSymbol'
        ));
    }
}
