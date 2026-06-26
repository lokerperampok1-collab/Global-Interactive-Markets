<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-white leading-tight flex items-center gap-2">
            <i class="fa fa-users text-teal-500"></i>
            {{ __('User Management Hub') }}
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

            @if ($errors->any())
                <div class="p-4 bg-red-950/60 border border-red-800 text-red-300 rounded-xl text-sm font-semibold flex flex-col gap-1.5 shadow-lg">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-center gap-2">
                            <i class="fa fa-times-circle text-red-500"></i>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="bg-slate-900/60 backdrop-blur-md border border-slate-800/80 rounded-2xl p-6 shadow-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 text-xs font-bold text-slate-400 uppercase tracking-widest">
                                <th class="pb-3 pl-2">Name</th>
                                <th class="pb-3">Email / ID</th>
                                <th class="pb-3">KYC / Phone</th>
                                <th class="pb-3">Wallet Balance</th>
                                <th class="pb-3">Withdraw Status</th>
                                <th class="pb-3">Adjust Balance</th>
                                <th class="pb-3 text-right pr-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50 text-sm">
                            @foreach($users as $user)
                                <tr class="hover:bg-slate-800/30 transition">
                                    <!-- Name/Role -->
                                    <td class="py-4 pl-2">
                                        <div class="font-bold text-white">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-400 capitalize">
                                            @if($user->role === 'admin')
                                                <span class="text-teal-400 font-semibold"><i class="fa fa-shield"></i> {{ $user->role }}</span>
                                            @else
                                                <span>{{ $user->role }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <!-- Email -->
                                    <td class="py-4">
                                        <div class="text-slate-300 font-mono text-xs">{{ $user->email }}</div>
                                        <div class="text-[10px] text-slate-500">ID: {{ $user->id }}</div>
                                    </td>
                                    
                                    <!-- KYC / Phone -->
                                    <td class="py-4">
                                        <div class="text-slate-300">{{ $user->phone ?? 'No phone' }}</div>
                                        <div class="mt-1">
                                            @if($user->status_kyc === 'approved')
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-emerald-950/80 border border-emerald-800/80 text-emerald-400">
                                                    KYC: Verified
                                                </span>
                                            @elseif($user->status_kyc === 'pending')
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-yellow-950/80 border border-yellow-800/80 text-yellow-400">
                                                    KYC: Pending
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-slate-800 border border-slate-700 text-slate-400">
                                                    KYC: None
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <!-- Balance -->
                                    <td class="py-4 font-extrabold text-white text-base">
                                        {{ $user->currency_symbol ?? '$' }}{{ number_format($user->wallet->balance ?? 0.00, 2) }}
                                    </td>
                                    
                                    <!-- Withdrawal Capability -->
                                    <td class="py-4">
                                        <form method="POST" action="{{ route('admin.users.toggle_withdraw', $user->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 text-[10px] font-bold rounded-lg uppercase tracking-wider border transition
                                                {{ $user->is_withdraw_unlocked 
                                                    ? 'bg-emerald-950/60 text-emerald-400 border-emerald-800/60 hover:bg-emerald-900' 
                                                    : 'bg-red-950/60 text-red-400 border-red-800/60 hover:bg-red-900' }}">
                                                <i class="fa {{ $user->is_withdraw_unlocked ? 'fa-unlock' : 'fa-lock' }} mr-1"></i>
                                                {{ $user->is_withdraw_unlocked ? 'Unlocked' : 'Locked' }}
                                            </button>
                                        </form>
                                    </td>
 
                                    <!-- Balance Adjust Form -->
                                    <td class="py-4">
                                        <form method="POST" action="{{ route('admin.users.balance', $user->id) }}" class="flex items-center gap-1.5">
                                            @csrf
                                            <input type="number" name="amount" required step="any"
                                                class="w-24 px-2 py-1 text-xs border border-slate-700 bg-slate-950 text-slate-100 rounded focus:outline-none focus:ring-1 focus:ring-teal-500"
                                                placeholder="+/- $ USD">
                                            <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white text-xs px-3 py-1 rounded font-bold transition shadow" title="Adjust Balance">
                                                Adjust
                                            </button>
                                        </form>
                                    </td>
 
                                    <!-- Actions -->
                                    <td class="py-4 text-right pr-2">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <!-- Edit Profile -->
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-xs font-semibold bg-slate-800 border border-slate-700 hover:border-slate-600 text-slate-200 px-3 py-1.5 rounded-lg transition" title="Edit Profile">
                                                Edit
                                            </a>
 
                                            <!-- Reset Password -->
                                            <form method="POST" action="{{ route('admin.users.reset_password', $user->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-xs font-semibold bg-red-950/40 border border-red-900/50 hover:bg-red-900 text-red-300 hover:text-white px-2.5 py-1.5 rounded-lg transition" title="Reset user password to default">
                                                    Reset
                                                </button>
                                            </form>
 
                                            <!-- Impersonate -->
                                            @if($user->id !== Auth::id())
                                                <a href="{{ route('admin.users.impersonate', $user->id) }}" class="text-xs font-semibold bg-yellow-950/40 border border-yellow-900/50 hover:bg-yellow-900 text-yellow-300 hover:text-white px-2.5 py-1.5 rounded-lg transition" title="Login as user">
                                                    Impersonate
                                                </a>
                                                
                                                <!-- Delete User -->
                                                <form method="POST" action="{{ route('admin.users.delete', $user->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete user {{ $user->name }}? All their wallets, investments, transactions, and KYC logs will be deleted permanently. This action is IRREVERSIBLE.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs font-semibold bg-red-950/70 border border-red-800 text-red-400 hover:bg-red-900 hover:text-white px-2.5 py-1.5 rounded-lg transition" title="Delete User Account">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
 
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            </div>
 
        </div>
    </div>
</x-app-layout>
