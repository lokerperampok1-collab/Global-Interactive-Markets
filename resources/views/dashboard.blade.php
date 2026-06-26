@extends('layouts.member')

@section('content')
<div class="space-y-8 animate-fade-in-up">
    <!-- Welcome Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="text-sm text-slate-500">Monitor your global portfolio, purchase investment plans, and manage your wallet.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ Auth::user()->status_kyc === 'approved' ? 'bg-green-100 text-green-800' : (Auth::user()->status_kyc === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                KYC Status: {{ ucfirst(Auth::user()->status_kyc) }}
            </span>
            @if(Auth::user()->status_kyc !== 'approved')
                <a href="{{ route('kyc.index') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-800 underline">
                    Complete KYC
                </a>
            @endif
        </div>
    </div>

    <!-- Financial Metrics Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Balance -->
        <div class="premium-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-400">
                <span class="text-xs font-semibold uppercase tracking-wider">Current Balance</span>
                <i class="fa fa-google-wallet text-xl text-teal-600"></i>
            </div>
            <div class="mt-4">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ Auth::user()->currency_symbol }}{{ number_format($balance, 2) }}</span>
                <span class="text-xs text-slate-400 block mt-1">Base Currency: {{ Auth::user()->currency_code }}</span>
            </div>
        </div>

        <!-- Active Investment -->
        <div class="premium-card p-6 bg-gradient-premium text-white flex flex-col justify-between border-none">
            <div class="flex items-center justify-between text-teal-200">
                <span class="text-xs font-semibold uppercase tracking-wider">Active Investments</span>
                <i class="fa fa-line-chart text-xl"></i>
            </div>
            <div class="mt-4">
                <span class="text-2xl sm:text-3xl font-extrabold">{{ Auth::user()->currency_symbol }}{{ number_format($activeInvestment, 2) }}</span>
                <span class="text-xs text-teal-200 block mt-1">Earning yield automatically</span>
            </div>
        </div>

        <!-- Total Profit -->
        <div class="premium-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-400">
                <span class="text-xs font-semibold uppercase tracking-wider">Total Profit</span>
                <i class="fa fa-money text-xl text-emerald-600"></i>
            </div>
            <div class="mt-4">
                <span class="text-2xl sm:text-3xl font-extrabold text-emerald-600">+{{ Auth::user()->currency_symbol }}{{ number_format($totalProfit, 2) }}</span>
                <span class="text-xs text-slate-400 block mt-1">From matured plans</span>
            </div>
        </div>

        <!-- Total Deposit -->
        <div class="premium-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-400">
                <span class="text-xs font-semibold uppercase tracking-wider">Total Deposit</span>
                <i class="fa fa-arrow-circle-down text-xl text-blue-600"></i>
            </div>
            <div class="mt-4">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ Auth::user()->currency_symbol }}{{ number_format($totalDeposit, 2) }}</span>
                <span class="text-xs text-slate-400 block mt-1">Successfully approved</span>
            </div>
        </div>

        <!-- Total Withdrawal -->
        <div class="premium-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-400">
                <span class="text-xs font-semibold uppercase tracking-wider">Total Withdrawal</span>
                <i class="fa fa-arrow-circle-up text-xl text-indigo-600"></i>
            </div>
            <div class="mt-4">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ Auth::user()->currency_symbol }}{{ number_format($totalWithdrawal, 2) }}</span>
                <span class="text-xs text-slate-400 block mt-1">Paid out to bank</span>
            </div>
        </div>

        <!-- Total Investment -->
        <div class="premium-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-400">
                <span class="text-xs font-semibold uppercase tracking-wider">Total Investments Purchased</span>
                <i class="fa fa-cubes text-xl text-purple-600"></i>
            </div>
            <div class="mt-4">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ Auth::user()->currency_symbol }}{{ number_format($totalInvestment, 2) }}</span>
                <span class="text-xs text-slate-400 block mt-1">All time purchase capital</span>
            </div>
        </div>
    </div>

    <!-- TradingView Chart & Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart -->
        <div class="lg:col-span-2 premium-card p-6">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">
                <i class="fa fa-area-chart text-teal-600"></i> Market Chart — {{ $symbolName }}
            </h3>
            
            <!-- TradingView Widget Container -->
            <div class="w-full h-80 rounded-lg overflow-hidden border border-slate-200">
                <!-- TradingView Widget BEGIN -->
                <div class="tradingview-widget-container" style="height:100%;width:100%;">
                    <div id="tradingview_chart" style="height:100%;width:100%;"></div>
                    <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
                    <script type="text/javascript">
                    new TradingView.widget(
                    {
                        "autosize": true,
                        "symbol": "{{ $tvSymbol }}",
                        "interval": "D",
                        "timezone": "Etc/UTC",
                        "theme": "light",
                        "style": "1",
                        "locale": "en",
                        "toolbar_bg": "#f1f5f9",
                        "enable_publishing": false,
                        "hide_side_toolbar": false,
                        "allow_symbol_change": true,
                        "container_id": "tradingview_chart"
                    }
                    );
                    </script>
                </div>
                <!-- TradingView Widget END -->
            </div>
        </div>

        <!-- Quick Access / Info Panel -->
        <div class="premium-card p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">Quick Navigation</h3>
                <div class="space-y-3">
                    <a href="{{ route('investment.index') }}" class="flex items-center gap-3 p-3 rounded-lg border border-slate-100 hover:bg-slate-50 transition">
                        <i class="fa fa-line-chart text-teal-600 text-lg"></i>
                        <div>
                            <span class="block text-xs font-bold text-slate-800">Browse Investment Plans</span>
                            <span class="block text-[10px] text-slate-400">View packages up to VVIP tiers</span>
                        </div>
                    </a>

                    <a href="{{ route('wallet.deposit') }}" class="flex items-center gap-3 p-3 rounded-lg border border-slate-100 hover:bg-slate-50 transition">
                        <i class="fa fa-arrow-circle-down text-blue-600 text-lg"></i>
                        <div>
                            <span class="block text-xs font-bold text-slate-800">Deposit Funds</span>
                            <span class="block text-[10px] text-slate-400">Submit pending deposit requests</span>
                        </div>
                    </a>

                    <a href="{{ route('wallet.transfer') }}" class="flex items-center gap-3 p-3 rounded-lg border border-slate-100 hover:bg-slate-50 transition">
                        <i class="fa fa-exchange text-indigo-600 text-lg"></i>
                        <div>
                            <span class="block text-xs font-bold text-slate-800">Transfer peer-to-peer</span>
                            <span class="block text-[10px] text-slate-400">Transfer immediately at no cost</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="mt-6 border-t border-slate-100 pt-4 text-xs text-slate-400 leading-relaxed">
                <i class="fa fa-info-circle text-teal-600"></i> All transactions are subjected to instant audit. Ensure your KYC status is approved to qualify for swift withdrawals.
            </div>
        </div>
    </div>
</div>
@endsection
