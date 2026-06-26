@extends('layouts.member')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Investment History</h1>
            <p class="text-sm text-slate-500">View all your past and current investment records.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('investment.index') }}" class="btn-primary text-xs flex items-center gap-1.5 py-2 px-4">
                <i class="fa fa-line-chart"></i>
                <span>Browse Plans</span>
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Total Invested -->
        <div class="premium-card p-4">
            <div class="flex items-center gap-2 text-slate-400 mb-2">
                <i class="fa fa-cubes text-purple-500"></i>
                <span class="text-[10px] font-bold uppercase tracking-wider">Total Invested</span>
            </div>
            <span class="text-lg font-extrabold text-slate-900">{{ Auth::user()->currency_symbol }}{{ number_format($totalInvested, 2) }}</span>
        </div>

        <!-- Total Returns -->
        <div class="premium-card p-4">
            <div class="flex items-center gap-2 text-slate-400 mb-2">
                <i class="fa fa-money text-emerald-500"></i>
                <span class="text-[10px] font-bold uppercase tracking-wider">Total Returns</span>
            </div>
            <span class="text-lg font-extrabold text-emerald-600">+{{ Auth::user()->currency_symbol }}{{ number_format($totalReturn, 2) }}</span>
        </div>

        <!-- Active -->
        <div class="premium-card p-4">
            <div class="flex items-center gap-2 text-slate-400 mb-2">
                <i class="fa fa-clock-o text-teal-500"></i>
                <span class="text-[10px] font-bold uppercase tracking-wider">Active</span>
            </div>
            <span class="text-lg font-extrabold text-teal-600">{{ $activeCount }}</span>
        </div>

        <!-- Matured -->
        <div class="premium-card p-4">
            <div class="flex items-center gap-2 text-slate-400 mb-2">
                <i class="fa fa-check-circle text-green-500"></i>
                <span class="text-[10px] font-bold uppercase tracking-wider">Matured</span>
            </div>
            <span class="text-lg font-extrabold text-green-600">{{ $maturedCount }}</span>
        </div>
    </div>

    <!-- Investment Records -->
    @if($investments->isEmpty())
        <div class="premium-card p-12 text-center">
            <div class="text-6xl text-slate-200 mb-4">
                <i class="fa fa-history"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-500 mb-2">No Investment History</h3>
            <p class="text-sm text-slate-400 mb-6">You haven't made any investments yet. Start earning returns by purchasing an investment plan.</p>
            <a href="{{ route('investment.index') }}" class="btn-primary text-sm px-6 py-2.5 inline-flex items-center gap-2">
                <i class="fa fa-shopping-cart"></i>
                <span>Browse Investment Plans</span>
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($investments as $inv)
                @php
                    $isActive = $inv->status === 'active';
                    $isMatured = $inv->status === 'completed';
                    $isCancelled = $inv->status === 'cancelled';

                    $borderColor = $isActive ? 'border-l-teal-500' : ($isMatured ? 'border-l-emerald-500' : 'border-l-red-400');
                    $statusBg = $isActive ? 'bg-teal-100 text-teal-800' : ($isMatured ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800');
                    $statusIcon = $isActive ? 'fa-clock-o' : ($isMatured ? 'fa-check-circle' : 'fa-times-circle');
                    $statusLabel = $isActive ? 'Active' : ($isMatured ? 'Completed' : 'Cancelled');

                    $timeRemaining = $isActive ? max(0, $inv->end_at->timestamp - now()->timestamp) : 0;
                @endphp

                <div class="premium-card p-5 border-l-4 {{ $borderColor }} hover:shadow-lg transition-shadow duration-200"
                     @if($isActive)
                     x-data="{
                        timeLeft: {{ $timeRemaining }},
                        formatTime() {
                            if (this.timeLeft <= 0) return 'Matured — Reload';
                            let h = Math.floor(this.timeLeft / 3600);
                            let m = Math.floor((this.timeLeft % 3600) / 60);
                            let s = this.timeLeft % 60;
                            return `${h}h ${m}m ${s}s`;
                        }
                     }"
                     x-init="setInterval(() => { if (timeLeft > 0) timeLeft-- }, 1000)"
                     @endif
                >
                    <!-- Top Row: Plan Name + Status Badge -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                        <div class="flex items-center gap-2">
                            <span class="text-base font-extrabold text-slate-900">{{ $inv->plan_name }}</span>
                            <span class="px-2 py-0.5 text-[9px] font-bold rounded uppercase tracking-wider {{ $statusBg }}">
                                <i class="fa {{ $statusIcon }}"></i> {{ $statusLabel }}
                            </span>
                        </div>
                        <span class="text-[10px] text-slate-400 font-medium">
                            <i class="fa fa-calendar"></i> {{ $inv->created_at->format('M d, Y — H:i') }}
                        </span>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 bg-slate-50 rounded-lg p-4 border border-slate-100">
                        <!-- Capital -->
                        <div>
                            <span class="block text-[10px] text-slate-400 uppercase tracking-wider font-bold mb-1">Capital</span>
                            <span class="text-sm font-extrabold text-slate-800">{{ Auth::user()->currency_symbol }}{{ number_format($inv->amount, 2) }}</span>
                        </div>

                        <!-- Target Return -->
                        <div>
                            <span class="block text-[10px] text-slate-400 uppercase tracking-wider font-bold mb-1">Target Return</span>
                            <span class="text-sm font-extrabold text-emerald-600">+{{ Auth::user()->currency_symbol }}{{ number_format($inv->target_return, 2) }}</span>
                        </div>

                        <!-- Duration -->
                        <div>
                            <span class="block text-[10px] text-slate-400 uppercase tracking-wider font-bold mb-1">Duration</span>
                            <span class="text-sm font-bold text-slate-700">{{ $inv->duration_days }} Hours</span>
                        </div>

                        <!-- Time Remaining / Maturity Date -->
                        <div>
                            @if($isActive)
                                <span class="block text-[10px] text-slate-400 uppercase tracking-wider font-bold mb-1">Time Left</span>
                                <span class="text-sm font-extrabold text-teal-600" x-text="formatTime()">Calculating...</span>
                            @elseif($isMatured)
                                <span class="block text-[10px] text-slate-400 uppercase tracking-wider font-bold mb-1">Completed On</span>
                                <span class="text-sm font-bold text-emerald-700">{{ $inv->end_at->format('M d, Y') }}</span>
                            @else
                                <span class="block text-[10px] text-slate-400 uppercase tracking-wider font-bold mb-1">Status</span>
                                <span class="text-sm font-bold text-red-600">Cancelled</span>
                            @endif
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="flex items-center gap-2 mt-3 text-[10px] text-slate-400">
                        <span><i class="fa fa-play-circle text-teal-500"></i> {{ $inv->start_at->format('M d, H:i') }}</span>
                        <span class="flex-grow border-t border-dashed border-slate-200"></span>
                        <span><i class="fa fa-flag-checkered {{ $isMatured ? 'text-emerald-500' : 'text-slate-400' }}"></i> {{ $inv->end_at->format('M d, H:i') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($investments->hasPages())
            <div class="mt-6 flex justify-center">
                <div class="inline-flex items-center gap-1">
                    {{-- Previous --}}
                    @if($investments->onFirstPage())
                        <span class="px-3 py-2 text-xs text-slate-300 bg-white border border-slate-200 rounded-lg cursor-not-allowed">
                            <i class="fa fa-chevron-left"></i> Prev
                        </span>
                    @else
                        <a href="{{ $investments->previousPageUrl() }}" class="px-3 py-2 text-xs text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                            <i class="fa fa-chevron-left"></i> Prev
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach($investments->getUrlRange(1, $investments->lastPage()) as $page => $url)
                        @if($page == $investments->currentPage())
                            <span class="px-3 py-2 text-xs font-bold text-white bg-teal-600 rounded-lg shadow-sm">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-2 text-xs text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if($investments->hasMorePages())
                        <a href="{{ $investments->nextPageUrl() }}" class="px-3 py-2 text-xs text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                            Next <i class="fa fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="px-3 py-2 text-xs text-slate-300 bg-white border border-slate-200 rounded-lg cursor-not-allowed">
                            Next <i class="fa fa-chevron-right"></i>
                        </span>
                    @endif
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
