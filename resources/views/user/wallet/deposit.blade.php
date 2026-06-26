@extends('layouts.member')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Deposit Funds</h1>
        <p class="text-sm text-slate-500">Add funds to your Global Interactive Markets digital wallet. Admin approval is required.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Form Panel -->
        <div class="md:col-span-1 premium-card p-6 h-fit">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">Request Deposit</h2>
            
            <form method="POST" action="{{ route('wallet.deposit.post') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="amount" class="block text-xs font-bold text-slate-700 uppercase mb-2">Deposit Amount ({{ Auth::user()->currency_code }})</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-bold">{{ Auth::user()->currency_symbol }}</span>
                        <input type="number" name="amount" id="amount" min="10" step="any" required
                            class="block w-full pl-8 pr-3 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 text-sm font-semibold"
                            placeholder="Min. 10.00">
                    </div>
                    <span class="text-[10px] text-slate-400 block mt-1">Minimum deposit is {{ Auth::user()->currency_symbol }}10.00 {{ Auth::user()->currency_code }}</span>
                </div>

                <button type="submit" class="btn-primary w-full text-sm">
                    Submit Request
                </button>
            </form>
        </div>

        <!-- History Panel -->
        <div class="md:col-span-2 premium-card p-6">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">Deposit History</h2>
            
            @if($transactions->isEmpty())
                <div class="text-center py-12 text-slate-400">
                    <i class="fa fa-history text-3xl mb-3 block"></i>
                    <span class="text-sm">No deposits requested yet.</span>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 text-xs font-bold text-slate-400 uppercase">
                                <th class="pb-3">Transaction ID</th>
                                <th class="pb-3">Amount</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3 text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach($transactions as $tx)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 font-mono text-xs text-slate-500">#TX-{{ str_pad($tx->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td class="py-3 font-bold text-slate-900">{{ Auth::user()->currency_symbol }}{{ number_format($tx->amount, 2) }}</td>
                                    <td class="py-3">
                                        <span class="px-2 py-0.5 rounded text-xs font-semibold
                                            {{ $tx->status === 'approved' ? 'bg-green-100 text-green-800' : ($tx->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($tx->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right text-xs text-slate-400">{{ $tx->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
