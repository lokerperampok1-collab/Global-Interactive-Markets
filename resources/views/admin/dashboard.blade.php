<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-white leading-tight flex items-center justify-between">
            <span class="flex items-center gap-2">
                <i class="fa fa-dashboard text-teal-500"></i>
                {{ __('Admin Command Center') }}
            </span>
            <a href="{{ route('dashboard') }}" class="text-xs font-bold bg-teal-950 text-teal-400 border border-teal-800 px-4 py-2 rounded-lg hover:bg-teal-900 transition flex items-center gap-1.5 shadow">
                <i class="fa fa-home"></i> Go to Member Dashboard &rarr;
            </a>
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Status Alerts -->
            @if (session('status'))
                <div class="p-4 bg-teal-950/60 border border-teal-800 text-teal-300 rounded-xl text-sm font-semibold flex items-center gap-2.5 shadow-lg">
                    <i class="fa fa-check-circle text-teal-500"></i>
                    {{ session('status') }}
                </div>
            @endif

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Users -->
                <div class="bg-slate-900/60 backdrop-blur-md border border-slate-800/80 rounded-2xl p-6 shadow-xl relative overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:border-teal-500/20 group">
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-teal-500/5 rounded-full blur-2xl group-hover:bg-teal-500/10 transition duration-300 pointer-events-none"></div>
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-bold uppercase tracking-wider">Total Active Users</span>
                        <div class="w-10 h-10 rounded-lg bg-teal-500/10 flex items-center justify-center text-teal-500">
                            <i class="fa fa-users text-lg"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-white tracking-tight">{{ $totalUsers }}</span>
                        <span class="text-xs text-slate-400">Registered members</span>
                    </div>
                    <a href="{{ route('admin.users') }}" class="text-xs font-bold text-teal-400 hover:text-teal-300 uppercase tracking-wider flex items-center gap-1.5 mt-6 transition">
                        <span>Manage Users</span>
                        <i class="fa fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Pending KYC Requests -->
                <div class="bg-slate-900/60 backdrop-blur-md border border-slate-800/80 rounded-2xl p-6 shadow-xl relative overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:border-yellow-500/20 group">
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-yellow-500/5 rounded-full blur-2xl group-hover:bg-yellow-500/10 transition duration-300 pointer-events-none"></div>
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-bold uppercase tracking-wider">Pending KYC Audits</span>
                        <div class="w-10 h-10 rounded-lg bg-yellow-500/10 flex items-center justify-center text-yellow-500">
                            <i class="fa fa-id-card text-lg"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-white tracking-tight">{{ $pendingKyc }}</span>
                        <span class="text-xs text-slate-400">Awaiting review</span>
                    </div>
                    <a href="{{ route('admin.kyc') }}" class="text-xs font-bold text-yellow-400 hover:text-yellow-300 uppercase tracking-wider flex items-center gap-1.5 mt-6 transition">
                        <span>Verify Documents</span>
                        <i class="fa fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Pending Transactions -->
                <div class="bg-slate-900/60 backdrop-blur-md border border-slate-800/80 rounded-2xl p-6 shadow-xl relative overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500/20 group">
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl group-hover:bg-emerald-500/10 transition duration-300 pointer-events-none"></div>
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-bold uppercase tracking-wider">Pending Transactions</span>
                        <div class="w-10 h-10 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                            <i class="fa fa-exchange text-lg"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-white tracking-tight">{{ $pendingTx }}</span>
                        <span class="text-xs text-slate-400">Deposits & Withdrawals</span>
                    </div>
                    <a href="{{ route('admin.wallet') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 uppercase tracking-wider flex items-center gap-1.5 mt-6 transition">
                        <span>Manage Transactions</span>
                        <i class="fa fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-slate-900/60 backdrop-blur-md border border-slate-800/80 rounded-2xl p-6 shadow-xl">
                <h3 class="font-extrabold text-slate-200 text-xs uppercase tracking-widest mb-4 flex items-center gap-1.5">
                    <i class="fa fa-cubes text-teal-500"></i> Platform Administration
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <a href="{{ route('admin.plans.index') }}" class="p-5 bg-slate-950/40 border border-slate-800 rounded-xl hover:border-teal-500/40 hover:bg-slate-900/80 transition duration-350 text-center block group shadow-sm">
                        <div class="w-12 h-12 rounded-lg bg-teal-500/10 flex items-center justify-center text-teal-400 mx-auto mb-3 group-hover:scale-110 transition duration-300">
                            <i class="fa fa-line-chart text-xl"></i>
                        </div>
                        <span class="block text-xs font-bold text-slate-200">Investment Plans</span>
                        <span class="block text-[10px] text-slate-500 mt-1">Tier Packages CRUD</span>
                    </a>
                    
                    <a href="{{ route('admin.users') }}" class="p-5 bg-slate-950/40 border border-slate-800 rounded-xl hover:border-teal-500/40 hover:bg-slate-900/80 transition duration-350 text-center block group shadow-sm">
                        <div class="w-12 h-12 rounded-lg bg-teal-500/10 flex items-center justify-center text-teal-400 mx-auto mb-3 group-hover:scale-110 transition duration-300">
                            <i class="fa fa-users text-xl"></i>
                        </div>
                        <span class="block text-xs font-bold text-slate-200">User Profiles</span>
                        <span class="block text-[10px] text-slate-500 mt-1">Audit, Impersonate, Adjust</span>
                    </a>

                    <a href="{{ route('admin.kyc') }}" class="p-5 bg-slate-950/40 border border-slate-800 rounded-xl hover:border-teal-500/40 hover:bg-slate-900/80 transition duration-350 text-center block group shadow-sm">
                        <div class="w-12 h-12 rounded-lg bg-teal-500/10 flex items-center justify-center text-teal-400 mx-auto mb-3 group-hover:scale-110 transition duration-300">
                            <i class="fa fa-id-card text-xl"></i>
                        </div>
                        <span class="block text-xs font-bold text-slate-200">KYC Approvals</span>
                        <span class="block text-[10px] text-slate-500 mt-1">Verify ID & Selfies</span>
                    </a>

                    <a href="{{ route('admin.wallet') }}" class="p-5 bg-slate-950/40 border border-slate-800 rounded-xl hover:border-teal-500/40 hover:bg-slate-900/80 transition duration-350 text-center block group shadow-sm">
                        <div class="w-12 h-12 rounded-lg bg-teal-500/10 flex items-center justify-center text-teal-400 mx-auto mb-3 group-hover:scale-110 transition duration-300">
                            <i class="fa fa-exchange text-xl"></i>
                        </div>
                        <span class="block text-xs font-bold text-slate-200">Wallet Auditing</span>
                        <span class="block text-[10px] text-slate-500 mt-1">Deposits & Withdrawals</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
