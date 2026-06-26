<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-white leading-tight flex items-center gap-2">
            <i class="fa fa-cubes text-teal-500"></i>
            {{ __('Create New Investment Plan') }}
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

                <form method="POST" action="{{ route('admin.plans.store') }}" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tier -->
                        <div>
                            <x-input-label for="tier" :value="__('Plan Tier')" class="text-slate-300" />
                            <select id="tier" name="tier" required class="mt-1 block w-full bg-slate-950 border-slate-700 text-slate-100 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm py-2 px-3">
                                <option value="BASIC">BASIC</option>
                                <option value="GOLD">GOLD</option>
                                <option value="DIAMOND">DIAMOND</option>
                                <option value="VVIP">VVIP</option>
                            </select>
                        </div>

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Plan Name')" class="text-slate-300" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-slate-950 border-slate-700 text-slate-100 focus:border-teal-500 focus:ring-teal-500 rounded-lg" :value="old('name')" required placeholder="e.g. BASIC 1" />
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <x-input-label for="description" :value="__('Description')" class="text-slate-300" />
                        <textarea id="description" name="description" rows="3" class="mt-1 block w-full bg-slate-950 border-slate-700 text-slate-100 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm py-2 px-3" placeholder="Enter plan details...">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Price -->
                        <div>
                            <x-input-label for="price" :value="__('Price (USD)')" class="text-slate-300" />
                            <x-text-input id="price" name="price" type="number" step="any" class="mt-1 block w-full bg-slate-950 border-slate-700 text-slate-100 focus:border-teal-500 focus:ring-teal-500 rounded-lg" :value="old('price')" required placeholder="500.00" />
                        </div>

                        <!-- Target Return -->
                        <div>
                            <x-input-label for="target_return" :value="__('Target Return (USD)')" class="text-slate-300" />
                            <x-text-input id="target_return" name="target_return" type="number" step="any" class="mt-1 block w-full bg-slate-950 border-slate-700 text-slate-100 focus:border-teal-500 focus:ring-teal-500 rounded-lg" :value="old('target_return')" required placeholder="15000.00" />
                        </div>

                        <!-- Duration -->
                        <div>
                            <x-input-label for="duration_days" :value="__('Duration (Hours)')" class="text-slate-300" />
                            <x-text-input id="duration_days" name="duration_days" type="number" class="mt-1 block w-full bg-slate-950 border-slate-700 text-slate-100 focus:border-teal-500 focus:ring-teal-500 rounded-lg" :value="old('duration_days', 3)" required />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Sort Order -->
                        <div>
                            <x-input-label for="sort_order" :value="__('Sort Order')" class="text-slate-300" />
                            <x-text-input id="sort_order" name="sort_order" type="number" class="mt-1 block w-full bg-slate-950 border-slate-700 text-slate-100 focus:border-teal-500 focus:ring-teal-500 rounded-lg" :value="old('sort_order', 0)" required />
                        </div>

                        <!-- Status -->
                        <div class="flex items-center mt-8">
                            <input id="status" name="status" type="checkbox" value="1" checked class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-slate-700 bg-slate-950 rounded">
                            <label for="status" class="ms-2 text-sm font-semibold text-slate-300">Active / Visible to members</label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-800 pt-6">
                        <a href="{{ route('admin.plans.index') }}" class="text-xs font-bold text-slate-300 hover:text-white bg-slate-800 border border-slate-700 hover:border-slate-600 px-4 py-2.5 rounded-lg transition">
                            Cancel
                        </a>
                        <x-primary-button class="bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-lg transition shadow">
                            {{ __('Create Plan') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
