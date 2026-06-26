<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-white leading-tight flex items-center gap-2">
            <i class="fa fa-id-card text-teal-500"></i>
            {{ __('Pending KYC Document Review') }}
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

            @if($kycRequests->isEmpty())
                <div class="bg-slate-900/60 backdrop-blur-md border border-slate-800/80 rounded-2xl p-12 text-center text-slate-400">
                    <div class="w-16 h-16 rounded-full bg-slate-950 flex items-center justify-center mx-auto mb-4 border border-slate-800 text-slate-500">
                        <i class="fa fa-id-card text-2xl"></i>
                    </div>
                    <span class="text-sm font-semibold">No pending KYC verification requests.</span>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($kycRequests as $req)
                        <div class="bg-slate-900/60 backdrop-blur-md border border-slate-800/80 rounded-2xl p-6 border-l-4 border-l-yellow-500 shadow-xl">
                            <!-- User Details -->
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-slate-800 pb-4 mb-4 gap-4">
                                <div>
                                    <h3 class="text-base font-extrabold text-white flex items-center gap-2">
                                        <i class="fa fa-user text-slate-400"></i> {{ $req->user->name }}
                                    </h3>
                                    <span class="text-xs text-slate-400">Email: {{ $req->user->email }} | Phone: {{ $req->user->phone ?? 'N/A' }} | Country: {{ $req->user->country_name }}</span>
                                </div>
                                <span class="text-xs text-slate-500">Submitted: {{ $req->created_at->format('M d, Y H:i') }}</span>
                            </div>

                            <!-- Document Images Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <!-- Front -->
                                <div class="space-y-2">
                                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">ID Front Side</span>
                                    <div class="border border-slate-800 rounded-xl overflow-hidden h-48 bg-slate-950 flex items-center justify-center p-2 hover:border-teal-500/40 transition">
                                        @if($req->id_front_path)
                                            <a href="{{ asset('storage/' . $req->id_front_path) }}" target="_blank" class="w-full h-full flex items-center justify-center">
                                                <img src="{{ asset('storage/' . $req->id_front_path) }}" class="max-w-full max-h-full object-contain rounded" alt="ID Front">
                                            </a>
                                        @else
                                            <span class="text-xs text-red-400"><i class="fa fa-exclamation-triangle"></i> Missing Front Photo</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Back -->
                                <div class="space-y-2">
                                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">ID Back Side</span>
                                    <div class="border border-slate-800 rounded-xl overflow-hidden h-48 bg-slate-950 flex items-center justify-center p-2 hover:border-teal-500/40 transition">
                                        @if($req->id_back_path)
                                            <a href="{{ asset('storage/' . $req->id_back_path) }}" target="_blank" class="w-full h-full flex items-center justify-center">
                                                <img src="{{ asset('storage/' . $req->id_back_path) }}" class="max-w-full max-h-full object-contain rounded" alt="ID Back">
                                            </a>
                                        @else
                                            <span class="text-xs text-red-400"><i class="fa fa-exclamation-triangle"></i> Missing Back Photo</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Selfie -->
                                <div class="space-y-2">
                                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Selfie holding ID</span>
                                    <div class="border border-slate-800 rounded-xl overflow-hidden h-48 bg-slate-950 flex items-center justify-center p-2 hover:border-teal-500/40 transition">
                                        @if($req->selfie_path)
                                            <a href="{{ asset('storage/' . $req->selfie_path) }}" target="_blank" class="w-full h-full flex items-center justify-center">
                                                <img src="{{ asset('storage/' . $req->selfie_path) }}" class="max-w-full max-h-full object-contain rounded" alt="Selfie">
                                            </a>
                                        @else
                                            <span class="text-xs text-red-400"><i class="fa fa-exclamation-triangle"></i> Missing Selfie Photo</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-800 pt-4">
                                <!-- Rejection Form -->
                                <form method="POST" action="{{ route('admin.kyc.reject', $req->id) }}" class="flex items-center gap-2 w-full sm:w-auto">
                                    @csrf
                                    <input type="text" name="note" required
                                        class="px-3 py-2 border border-slate-700 bg-slate-950 text-slate-100 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs w-full sm:w-80"
                                        placeholder="Reason for rejection (note)...">
                                    <button type="submit" class="bg-red-950/60 border border-red-900/50 hover:bg-red-900 text-red-300 hover:text-white font-bold text-xs px-4 py-2.5 rounded-lg transition whitespace-nowrap">
                                        Reject KYC
                                    </button>
                                </form>

                                <!-- Approval Action -->
                                <form method="POST" action="{{ route('admin.kyc.approve', $req->id) }}">
                                    @csrf
                                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs px-6 py-2.5 rounded-lg transition shadow-md whitespace-nowrap">
                                        Verify &amp; Approve
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
