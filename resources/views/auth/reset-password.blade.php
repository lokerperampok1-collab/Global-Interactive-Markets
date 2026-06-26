<x-guest-layout>
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-extrabold text-white">Reset Password</h2>
        <p class="text-xs text-slate-400 mt-1">Create a new secure credentials password</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="space-y-2">
            <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                class="block w-full px-3 py-2.5 rounded-lg text-sm auth-input focus:outline-none">
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-500" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">New Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="block w-full px-3 py-2.5 rounded-lg text-sm auth-input focus:outline-none">
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-500" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-2">
            <label for="password_confirmation" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                class="block w-full px-3 py-2.5 rounded-lg text-sm auth-input focus:outline-none">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-red-500" />
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary w-full py-3 text-sm flex items-center justify-center gap-2 shadow-lg">
            <span>Reset Password</span>
        </button>
    </form>
</x-guest-layout>
