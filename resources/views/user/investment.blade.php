@extends('layouts.member')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Investment Packages</h1>
        <p class="text-sm text-slate-500">Select an investment tier to purchase. Capital is locked and will earn returns automatically.</p>
    </div>

    <!-- Tiers grid -->
    <div class="space-y-12">
        @php
            $groupedPlans = $plans->groupBy('tier');
            $tierColors = [
                'BASIC' => ['bg' => 'bg-slate-50', 'text' => 'text-slate-800', 'border' => 'border-slate-200', 'badge' => 'bg-slate-200 text-slate-800'],
                'GOLD' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-800', 'border' => 'border-yellow-200', 'badge' => 'bg-yellow-200 text-yellow-800'],
                'DIAMOND' => ['bg' => 'bg-cyan-50', 'text' => 'text-cyan-800', 'border' => 'border-cyan-200', 'badge' => 'bg-cyan-200 text-cyan-800'],
                'VVIP' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-800', 'border' => 'border-purple-200', 'badge' => 'bg-purple-200 text-purple-800']
            ];
        @endphp

        @foreach($groupedPlans as $tierName => $plansInTier)
            <div>
                <!-- Tier Title -->
                <div class="flex items-center gap-3 mb-6">
                    <span class="text-lg font-extrabold text-slate-800 tracking-wider uppercase">{{ $tierName }} TIER</span>
                    <span class="h-px bg-slate-200 flex-grow"></span>
                </div>

                <!-- Plans Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($plansInTier as $plan)
                        <div class="premium-card p-6 flex flex-col justify-between hover:scale-[1.02] transition duration-200 border-t-4
                            {{ $plan->tier === 'GOLD' ? 'border-t-yellow-400' : ($plan->tier === 'DIAMOND' ? 'border-t-cyan-500' : ($plan->tier === 'VVIP' ? 'border-t-purple-600' : 'border-t-slate-400')) }}">
                            
                            <div>
                                <!-- Plan Name & Tier Badge -->
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="font-extrabold text-slate-800 text-base leading-tight">{{ $plan->name }}</h3>
                                    <span class="px-2 py-0.5 text-[9px] font-bold rounded uppercase tracking-wider {{ $tierColors[$plan->tier]['badge'] }}">
                                        {{ $plan->tier }}
                                    </span>
                                </div>

                                <p class="text-slate-400 text-xs leading-relaxed mb-6">{{ $plan->description }}</p>

                                <div class="space-y-3 mb-6 bg-slate-50 p-4 rounded-lg border border-slate-100">
                                    <div class="flex justify-between items-center text-xs text-slate-500">
                                        <span>Investment Price:</span>
                                        <span class="font-extrabold text-slate-800 text-sm">{{ Auth::user()->currency_symbol }}{{ number_format($plan->price, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-xs text-slate-500">
                                        <span>Target Return:</span>
                                        <span class="font-extrabold text-emerald-600 text-sm">+{{ Auth::user()->currency_symbol }}{{ number_format($plan->target_return, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-xs text-slate-500 border-t border-slate-200 pt-2">
                                        <span>Lock Duration:</span>
                                        <span class="font-bold text-slate-700">3-6 Hours</span>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('investment.invest') }}">
                                @csrf
                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                <button type="submit" class="btn-primary w-full text-xs py-2.5 flex items-center justify-center gap-1.5 shadow-sm">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span>Invest Now</span>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
