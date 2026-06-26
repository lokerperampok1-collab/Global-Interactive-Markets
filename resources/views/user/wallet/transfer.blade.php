@extends('layouts.member')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Transfer Funds (P2P)</h1>
        <p class="text-sm text-slate-500">Send money instantly to other members by their email address. Transfers are processed automatically.</p>
    </div>

    <!-- Alert / Fee Notice -->
    <div class="p-4 bg-teal-50 border border-teal-200 text-teal-800 rounded-lg flex items-start gap-3">
        <i class="fa fa-info-circle text-lg mt-0.5"></i>
        <div>
            <span class="block text-sm font-bold">P2P Transfer Fee: Free</span>
            <span class="block text-xs mt-1">Peer-to-peer transfers are processed **instantly and free of charge**. Only the exact transfer amount will be deducted from your wallet balance.</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Form Panel -->
        <div class="md:col-span-1 premium-card p-6 h-fit">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">Send Funds</h2>

            <form method="POST" action="{{ route('wallet.transfer.post') }}" class="space-y-4" x-data="{ amount: 0, fee() { return '0.00'; }, total() { return (parseFloat(this.amount || 0)).toFixed(2); } }">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase mb-2">Recipient Email</label>
                    <input type="email" name="email" id="email" required
                        class="block w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 text-sm font-semibold"
                        placeholder="user@example.com">
                </div>

                <div>
                    <label for="amount" class="block text-xs font-bold text-slate-700 uppercase mb-2">Transfer Amount ({{ Auth::user()->currency_code }})</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-bold">{{ Auth::user()->currency_symbol }}</span>
                        <input type="number" name="amount" id="amount" min="1" step="any" required
                            x-model.number="amount"
                            class="block w-full pl-8 pr-3 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 text-sm font-semibold"
                            placeholder="Min. 1.00">
                    </div>
                </div>

                <!-- Live Fee Calculator -->
                <div class="bg-slate-50 rounded-lg p-3 text-xs space-y-1.5 text-slate-500 border border-slate-100">
                    <div class="flex justify-between">
                        <span>Transfer Amount:</span>
                        <span class="font-bold text-slate-800">{{ Auth::user()->currency_symbol }}<span x-text="parseFloat(amount || 0).toFixed(2)">0.00</span></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Transfer Fee:</span>
                        <span class="font-bold text-emerald-600">Free ({{ Auth::user()->currency_symbol }}0.00)</span>
                    </div>
                    <div class="flex justify-between border-t border-slate-200 pt-1.5 font-bold text-slate-800">
                        <span>Total Deduction:</span>
                        <span>{{ Auth::user()->currency_symbol }}<span x-text="total()">0.00</span></span>
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full text-sm flex items-center justify-center gap-2">
                    <i class="fa fa-share"></i>
                    <span>Send Instantly</span>
                </button>
            </form>
        </div>

        <!-- History Panel -->
        <div class="md:col-span-2 premium-card p-6">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">Transfer History</h2>

            @if($transactions->isEmpty())
                <div class="text-center py-12 text-slate-400">
                    <i class="fa fa-history text-3xl mb-3 block"></i>
                    <span class="text-sm">No transfers recorded yet.</span>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 text-xs font-bold text-slate-400 uppercase">
                                <th class="pb-3">Transaction ID</th>
                                <th class="pb-3">Type</th>
                                <th class="pb-3">Amount</th>
                                <th class="pb-3">Description</th>
                                <th class="pb-3 text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach($transactions as $tx)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 font-mono text-xs text-slate-500">#TX-{{ str_pad($tx->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td class="py-3">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                            {{ $tx->type === 'transfer_in' ? 'bg-green-100 text-green-800' : ($tx->type === 'transfer_out' ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-600') }}">
                                            {{ str_replace('_', ' ', $tx->type) }}
                                        </span>
                                    </td>
                                    <td class="py-3 font-bold
                                        {{ $tx->type === 'transfer_in' ? 'text-green-600' : ($tx->type === 'transfer_out' ? 'text-red-600' : 'text-slate-600') }}">
                                        {{ $tx->type === 'transfer_in' ? '+' : '-' }}{{ Auth::user()->currency_symbol }}{{ number_format($tx->amount, 2) }}
                                    </td>
                                    <td class="py-3 text-xs text-slate-500">{{ $tx->note }}</td>
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
