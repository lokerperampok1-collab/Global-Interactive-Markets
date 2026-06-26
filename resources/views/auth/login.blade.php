<x-guest-layout>
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-extrabold text-white">Welcome Back</h2>
        <p class="text-xs text-slate-400 mt-1">Log in to your Global Interactive Markets account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="block w-full px-3 py-2.5 rounded-lg text-sm auth-input focus:outline-none"
                placeholder="email@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-500" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-teal-400 hover:text-teal-300 transition" href="{{ route('password.request') }}">
                        Forgot?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="block w-full px-3 py-2.5 rounded-lg text-sm auth-input focus:outline-none"
                placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;">
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-500" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember" 
                    class="rounded border-slate-700 bg-slate-950 text-teal-600 focus:ring-teal-500 focus:ring-offset-slate-900 focus:ring-2 w-4 h-4">
                <span class="ms-2 text-xs text-slate-400 font-medium">Keep me logged in</span>
            </label>
        </div>

        <!-- Login Button -->
        <button type="submit" class="btn-primary w-full py-3 text-sm flex items-center justify-center gap-2 shadow-lg">
            <i class="fa fa-sign-in"></i>
            <span>Log In</span>
        </button>

        <!-- Redirect to Register -->
        @if (Route::has('register'))
            <div class="text-center pt-4 border-t border-slate-800 text-xs text-slate-400">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-teal-400 hover:text-teal-300 font-bold transition">
                    Register Here
                </a>
            </div>
        @endif
    </form>
</x-guest-layout>
