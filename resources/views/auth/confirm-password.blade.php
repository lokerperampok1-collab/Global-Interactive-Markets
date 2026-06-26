<x-guest-layout>
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-extrabold text-white">Confirm Password</h2>
        <p class="text-xs text-slate-400 mt-1">This is a secure area of the application. Please confirm your password before continuing.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <!-- Password -->
        <div class="space-y-2">
            <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="block w-full px-3 py-2.5 rounded-lg text-sm auth-input focus:outline-none"
                placeholder="Enter password to verify">
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-500" />
        </div>

        <button type="submit" class="btn-primary w-full py-3 text-sm flex items-center justify-center gap-2 shadow-lg">
            <span>Confirm Password</span>
        </button>
    </form>
</x-guest-layout>
