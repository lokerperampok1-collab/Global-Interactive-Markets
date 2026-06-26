@extends('layouts.member')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Account Profile</h1>
        <p class="text-sm text-slate-500">Update your account credentials, contact information, and bank withdrawal destinations.</p>
    </div>

    <div class="space-y-6">
        <div class="premium-card p-6">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="premium-card p-6">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="premium-card p-6">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
