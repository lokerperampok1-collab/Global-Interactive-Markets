@extends('layouts.member')

@section('content')
<div class="max-w-3xl mx-auto space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Know Your Customer (KYC)</h1>
        <p class="text-sm text-slate-500">Provide identity verification documents to unlock full withdrawal capabilities on the platform.</p>
    </div>

    <!-- Status Cards -->
    @if(Auth::user()->status_kyc === 'approved')
        <div class="premium-card p-8 bg-green-50 border border-green-200 text-center space-y-4">
            <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto shadow-sm">
                <i class="fa fa-check text-2xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-green-900">Verification Complete</h2>
                <p class="text-sm text-green-700 mt-1">Your identity has been verified. Withdrawals and transfers are fully enabled on your account.</p>
            </div>
        </div>

    @elseif(Auth::user()->status_kyc === 'pending')
        <div class="premium-card p-8 bg-yellow-50 border border-yellow-200 text-center space-y-4">
            <div class="w-16 h-16 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mx-auto shadow-sm">
                <i class="fa fa-hourglass-half text-2xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-yellow-900">Awaiting Admin Verification</h2>
                <p class="text-sm text-yellow-700 mt-1">Your uploaded documents are currently being audited by our administrators. We will verify your account shortly.</p>
            </div>
            @if($kycRequest)
                <div class="max-w-md mx-auto border-t border-yellow-200 pt-4 text-xs text-left text-slate-500 space-y-1">
                    <div>Front ID: <span class="font-mono">{{ basename($kycRequest->id_front_path) }}</span></div>
                    <div>Back ID: <span class="font-mono">{{ basename($kycRequest->id_back_path) }}</span></div>
                    <div>Selfie: <span class="font-mono">{{ basename($kycRequest->selfie_path) }}</span></div>
                </div>
            @endif
        </div>

    @else
        <!-- None or Rejected -->
        @if(Auth::user()->status_kyc === 'rejected')
            <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg flex items-start gap-3">
                <i class="fa fa-times-circle text-lg mt-0.5"></i>
                <div>
                    <span class="block text-sm font-bold">Verification Rejected</span>
                    <span class="block text-xs mt-1">Note from admin: <strong>{{ $kycRequest->note ?? 'Please upload clear photos of your official identity card.' }}</strong></span>
                </div>
            </div>
        @endif

        <div class="premium-card p-6">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-6">Upload Verification Documents</h2>
            
            <form method="POST" action="{{ route('kyc.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Front ID -->
                    <div class="space-y-2">
                        <label for="id_front" class="block text-xs font-bold text-slate-700 uppercase">National ID / Passport Front Side</label>
                        <input type="file" name="id_front" id="id_front" accept="image/*" required
                            class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 border border-slate-300 rounded-lg p-2 bg-slate-50">
                        <span class="text-[10px] text-slate-400 block">JPEG, PNG up to 5MB. Must be readable.</span>
                    </div>

                    <!-- Back ID -->
                    <div class="space-y-2">
                        <label for="id_back" class="block text-xs font-bold text-slate-700 uppercase">National ID / Passport Back Side</label>
                        <input type="file" name="id_back" id="id_back" accept="image/*" required
                            class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 border border-slate-300 rounded-lg p-2 bg-slate-50">
                        <span class="text-[10px] text-slate-400 block">JPEG, PNG up to 5MB. Must be readable.</span>
                    </div>
                </div>

                <!-- Selfie -->
                <div class="space-y-2 max-w-md">
                    <label for="selfie" class="block text-xs font-bold text-slate-700 uppercase">Selfie Photo holding ID</label>
                    <input type="file" name="selfie" id="selfie" accept="image/*" required
                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 border border-slate-300 rounded-lg p-2 bg-slate-50">
                    <span class="text-[10px] text-slate-400 block">JPEG, PNG up to 5MB. Face must be fully visible.</span>
                </div>

                <button type="submit" class="btn-primary flex items-center justify-center gap-2 text-sm">
                    <i class="fa fa-cloud-upload"></i>
                    <span>Submit Documents</span>
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
