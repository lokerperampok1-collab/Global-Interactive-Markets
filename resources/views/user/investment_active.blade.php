@extends('layouts.member')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Active Investments</h1>
            <p class="text-sm text-slate-500">Monitor your active investments below. Returns are credited automatically upon maturity.</p>
        </div>
        <div class="flex items-center gap-2 self-start md:self-auto">
            <a href="{{ route('investment.history') }}" class="text-xs flex items-center gap-1.5 py-2 px-3 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold transition">
                <i class="fa fa-history"></i>
                <span>History</span>
            </a>
            <a href="{{ route('dashboard') }}" class="btn-primary text-xs flex items-center gap-1.5 py-2">
                <i class="fa fa-refresh"></i>
                <span>Refresh Page</span>
            </a>
        </div>
    </div>

    <!-- Info Banner -->
    <div class="p-4 bg-teal-50 border border-teal-200 text-teal-800 rounded-lg flex items-start gap-3">
        <i class="fa fa-clock-o text-lg mt-0.5"></i>
        <div>
            <span class="block text-sm font-bold">Auto-Maturity Enabled</span>
            <span class="block text-xs mt-1">Our platform processes payouts automatically. Refreshing the dashboard or investment page after the countdown completes will trigger the settlement transaction.</span>
        </div>
    </div>

    <!-- Active Investments Grid -->
    <div class="grid grid-cols-1 gap-6">
        @foreach($activeInvestments as $inv)
            <div class="premium-card p-6 border-l-4 border-l-teal-500 flex flex-col md:flex-row justify-between items-start md:items-center gap-6"
                 x-data="{ 
                    timeLeft: {{ max(0, $inv->end_at->timestamp - now()->timestamp) }},
                    formatTime() {
                        if (this.timeLeft <= 0) return 'Matured (Reload Page)';
                        let hours = Math.floor(this.timeLeft / 3600);
                        let minutes = Math.floor((this.timeLeft % 3600) / 60);
                        let seconds = this.timeLeft % 60;
                        return `${hours}h ${minutes}m ${seconds}s`;
                    }
                 }"
                 x-init="setInterval(() => { if (timeLeft > 0) timeLeft-- }, 1000)">
                
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="text-base font-extrabold text-slate-900">{{ $inv->plan_name }}</span>
                        <span class="px-2 py-0.5 text-[9px] font-bold rounded bg-teal-100 text-teal-800 uppercase">Active</span>
                    </div>
                    <div class="text-xs text-slate-400">
                        Start: <strong>{{ $inv->start_at->format('M d, Y H:i:s') }}</strong> | End: <strong>{{ $inv->end_at->format('M d, Y H:i:s') }}</strong>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-6 w-full md:w-auto text-left">
                    <div>
                        <span class="block text-[10px] text-slate-400 uppercase tracking-wider font-bold">Capital</span>
                        <span class="text-sm font-extrabold text-slate-800">{{ Auth::user()->currency_symbol }}{{ number_format($inv->amount, 2) }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] text-slate-400 uppercase tracking-wider font-bold">Target Return</span>
                        <span class="text-sm font-extrabold text-emerald-600">{{ Auth::user()->currency_symbol }}{{ number_format($inv->target_return, 2) }}</span>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <span class="block text-[10px] text-slate-400 uppercase tracking-wider font-bold">Time Remaining</span>
                        <span class="text-sm font-extrabold text-teal-600 block" x-text="formatTime()">Calculated...</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
