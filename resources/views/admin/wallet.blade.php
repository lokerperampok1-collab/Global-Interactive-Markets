<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-white leading-tight flex items-center gap-2">
            <i class="fa fa-exchange text-teal-500"></i>
            {{ __('Pending Wallet Transactions Audit') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('status'))
                <div class="p-4 bg-teal-950/60 border border-teal-800 text-teal-300 rounded-xl text-sm font-semibold flex items-center gap-2 shadow-lg">
                    <i class="fa fa-check-circle text-teal-500"></i>
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-red-950/60 border border-red-800 text-red-300 rounded-xl text-sm font-semibold">
                    <i class="fa fa-times-circle mr-1"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            @if($transactions->isEmpty())
                <div class="bg-slate-900/60 backdrop-blur-md border border-slate-800/80 rounded-2xl p-12 text-center text-slate-400">
                    <div class="w-16 h-16 rounded-full bg-slate-950 flex items-center justify-center mx-auto mb-4 border border-slate-800 text-slate-500">
                        <i class="fa fa-exchange text-2xl"></i>
                    </div>
                    <span class="text-sm font-semibold">No pending transactions to audit.</span>
                </div>
            @else
                <div class="bg-slate-900/60 backdrop-blur-md border border-slate-800/80 rounded-2xl p-6 shadow-xl">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800 text-xs font-bold text-slate-400 uppercase tracking-widest">
                                    <th class="pb-3 pl-2">Transaction ID</th>
                                    <th class="pb-3">User Profile</th>
                                    <th class="pb-3">Type</th>
                                    <th class="pb-3">Amount (USD)</th>
                                    <th class="pb-3">Reference / Bank Details</th>
                                    <th class="pb-3">Submitted At</th>
                                    <th class="pb-3 text-right pr-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/50 text-sm">
                                @foreach($transactions as $tx)
                                    <tr class="hover:bg-slate-800/30 transition">
                                        <!-- ID -->
                                        <td class="py-4 pl-2 font-mono text-xs text-slate-400">
                                            #TX-{{ str_pad($tx->id, 6, '0', STR_PAD_LEFT) }}
                                        </td>
                                        
                                        <!-- User -->
                                        <td class="py-4">
                                            <div class="font-bold text-white">{{ $tx->user->name }}</div>
                                            <div class="text-xs text-slate-400 font-mono">{{ $tx->user->email }}</div>
                                        </td>

                                        <!-- Type -->
                                        <td class="py-4">
                                            @if($tx->type === 'deposit')
                                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-blue-950/80 border border-blue-800/80 text-blue-400">
                                                    {{ ucfirst($tx->type) }}
                                                </span>
                                            @else
                                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-indigo-950/80 border border-indigo-800/80 text-indigo-400">
                                                    {{ ucfirst($tx->type) }}
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Amount -->
                                        <td class="py-4 font-extrabold text-white text-base">
                                            {{ $tx->user->currency_symbol ?? '$' }}{{ number_format($tx->amount, 2) }}
                                        </td>

                                        <!-- Note / Details -->
                                        <td class="py-4 text-xs text-slate-300 max-w-xs truncate" title="{{ $tx->note }}">
                                            {{ $tx->note ?? 'No details provided' }}
                                        </td>

                                        <!-- Date -->
                                        <td class="py-4 text-xs text-slate-500">
                                            {{ $tx->created_at->format('M d, Y H:i') }}
                                        </td>

                                        <!-- Actions -->
                                        <td class="py-4 text-right pr-2">
                                            <div class="flex items-center justify-end gap-2">
                                                <!-- Reject -->
                                                <form method="POST" action="{{ route('admin.wallet.reject', $tx->id) }}">
                                                    @csrf
                                                    <button type="submit" class="bg-red-950/60 border border-red-900/50 hover:bg-red-900 text-red-300 hover:text-white font-bold text-xs px-3 py-2 rounded-lg transition shadow-sm">
                                                        Reject
                                                    </button>
                                                </form>

                                                <!-- Approve -->
                                                <form method="POST" action="{{ route('admin.wallet.approve', $tx->id) }}">
                                                    @csrf
                                                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs px-4 py-2 rounded-lg transition shadow-md">
                                                        Approve
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
