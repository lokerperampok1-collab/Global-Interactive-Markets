<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-white leading-tight flex items-center gap-2">
            <i class="fa fa-user-circle text-teal-500"></i>
            {{ __('Edit User Profile') }} &bull; <span class="text-teal-400">{{ $user->name }}</span>
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900/60 backdrop-blur-md border border-slate-800/80 rounded-2xl p-6 shadow-xl">
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-950/60 border border-red-800 text-red-300 rounded-lg text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <div><i class="fa fa-times-circle mr-1"></i> {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" class="text-slate-300" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-slate-950 border-slate-700 text-slate-100 focus:border-teal-500 focus:ring-teal-500 rounded-lg" :value="old('name', $user->name)" required />
                        </div>

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Email')" class="text-slate-300" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full bg-slate-950 border-slate-700 text-slate-100 focus:border-teal-500 focus:ring-teal-500 rounded-lg" :value="old('email', $user->email)" required />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Phone -->
                        <div>
                            <x-input-label for="phone" :value="__('Phone Number')" class="text-slate-300" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full bg-slate-950 border-slate-700 text-slate-100 focus:border-teal-500 focus:ring-teal-500 rounded-lg" :value="old('phone', $user->phone)" required />
                        </div>

                        <!-- Role -->
                        <div>
                            <x-input-label for="role" :value="__('Access Role')" class="text-slate-300" />
                            <select id="role" name="role" class="mt-1 block w-full bg-slate-950 border-slate-700 text-slate-100 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm py-2 px-3">
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User (Ahli)</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                    </div>

                    <div class="border-t border-slate-800 pt-6">
                        <h3 class="text-xs font-extrabold text-slate-300 uppercase tracking-widest mb-4 flex items-center gap-1.5">
                            <i class="fa fa-bank text-teal-500"></i> Bank Withdrawal Account
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Bank Name -->
                            <div>
                                <x-input-label for="bank_name" :value="__('Bank Name')" class="text-slate-300" />
                                <x-text-input id="bank_name" name="bank_name" type="text" class="mt-1 block w-full bg-slate-950 border-slate-700 text-slate-100 focus:border-teal-500 focus:ring-teal-500 rounded-lg" :value="old('bank_name', $user->bank_name)" />
                            </div>

                            <!-- Bank Account -->
                            <div>
                                <x-input-label for="bank_account" :value="__('Bank Account Number')" class="text-slate-300" />
                                <x-text-input id="bank_account" name="bank_account" type="text" class="mt-1 block w-full bg-slate-950 border-slate-700 text-slate-100 focus:border-teal-500 focus:ring-teal-500 rounded-lg" :value="old('bank_account', $user->bank_account)" />
                            </div>

                            <!-- SWIFT -->
                            <div>
                                <x-input-label for="swift_code" :value="__('SWIFT / BIC Code')" class="text-slate-300" />
                                <x-text-input id="swift_code" name="swift_code" type="text" class="mt-1 block w-full bg-slate-950 border-slate-700 text-slate-100 focus:border-teal-500 focus:ring-teal-500 rounded-lg" :value="old('swift_code', $user->swift_code)" />
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-slate-800 pt-6">
                        <h3 class="text-xs font-extrabold text-slate-300 uppercase tracking-widest mb-4 flex items-center gap-1.5">
                            <i class="fa fa-id-card text-teal-500"></i> Know Your Customer (KYC) Status
                        </h3>
                        
                        <div>
                            <label for="status_kyc" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">KYC Verification State</label>
                            <select id="status_kyc" name="status_kyc" class="mt-1 block w-full max-w-xs bg-slate-950 border-slate-700 text-slate-100 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm py-2 px-3">
                                <option value="none" {{ $user->status_kyc === 'none' ? 'selected' : '' }}>None (Belum upload)</option>
                                <option value="pending" {{ $user->status_kyc === 'pending' ? 'selected' : '' }}>Pending (Awaiting audit)</option>
                                <option value="approved" {{ $user->status_kyc === 'approved' ? 'selected' : '' }}>Approved (Verified)</option>
                                <option value="rejected" {{ $user->status_kyc === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-800 pt-6">
                        <a href="{{ route('admin.users') }}" class="text-xs font-bold text-slate-300 hover:text-white bg-slate-800 border border-slate-700 hover:border-slate-600 px-4 py-2.5 rounded-lg transition">
                            Cancel
                        </a>
                        <x-primary-button class="bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-lg transition shadow">
                            {{ __('Save Profile') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
