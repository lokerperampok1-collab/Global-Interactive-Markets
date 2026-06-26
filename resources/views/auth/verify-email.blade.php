<x-guest-layout>
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-extrabold text-white">Verify Email</h2>
        <p class="text-xs text-slate-400 mt-1">Please confirm your email address to unlock account operations.</p>
    </div>

    <div class="mb-5 text-xs text-slate-400 leading-relaxed bg-slate-950/40 p-3.5 rounded-lg border border-slate-800">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-5 p-3 text-xs bg-teal-950/60 border border-teal-800 text-teal-300 rounded-lg">
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <div class="mt-6 flex flex-col gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-primary w-full py-3 text-sm flex items-center justify-center gap-2 shadow-lg">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 py-3 rounded-lg text-sm font-bold transition">
                Log Out
            </button>
        </form>
    </div>
</x-guest-layout>
