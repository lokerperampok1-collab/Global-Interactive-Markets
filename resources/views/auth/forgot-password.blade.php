<x-guest-layout>
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-extrabold text-white">Reset Password</h2>
        <p class="text-xs text-slate-400 mt-1">Request a secure password reset link for your account</p>
    </div>

    <div class="mb-5 text-xs text-slate-400 leading-relaxed bg-slate-950/40 p-3.5 rounded-lg border border-slate-800">
        Forgot your password? No problem. Enter your registered email address and we will send you a password reset link to create a new credentials profile.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="block w-full px-3 py-2.5 rounded-lg text-sm auth-input focus:outline-none"
                placeholder="email@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-500" />
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary w-full py-3 text-sm flex items-center justify-center gap-2 shadow-lg">
            <i class="fa fa-envelope-o"></i>
            <span>Send Reset Link</span>
        </button>

        <!-- Redirect to Login -->
        <div class="text-center pt-4 border-t border-slate-800 text-xs text-slate-400">
            Remembered your credentials? 
            <a href="{{ route('login') }}" class="text-teal-400 hover:text-teal-300 font-bold transition">
                Log In Here
            </a>
        </div>
    </form>
</x-guest-layout>
