<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-white leading-tight flex items-center justify-between">
            <span class="flex items-center gap-2">
                <i class="fa fa-cubes text-teal-500"></i>
                {{ __('Investment Plan Packages') }}
            </span>
            <a href="{{ route('admin.plans.create') }}" class="text-xs font-bold bg-teal-600 hover:bg-teal-700 text-white px-4 py-2.5 rounded-lg transition shadow-md flex items-center gap-1">
                <i class="fa fa-plus"></i> Create New Plan
            </a>
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

            <div class="bg-slate-900/60 backdrop-blur-md border border-slate-800/80 rounded-2xl p-6 shadow-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 text-xs font-bold text-slate-400 uppercase tracking-widest">
                                <th class="pb-3 pl-2">Sort</th>
                                <th class="pb-3">Plan Name</th>
                                <th class="pb-3">Tier</th>
                                <th class="pb-3">Price</th>
                                <th class="pb-3">Target Return</th>
                                <th class="pb-3">Duration</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3 text-right pr-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50 text-sm">
                            @foreach($plans as $plan)
                                <tr class="hover:bg-slate-800/30 transition">
                                    <!-- Sort Order -->
                                    <td class="py-4 pl-2 text-slate-500 font-mono text-xs">
                                        #{{ $plan->sort_order }}
                                    </td>

                                    <!-- Plan Name -->
                                    <td class="py-4">
                                        <div class="font-bold text-white">{{ $plan->name }}</div>
                                        <div class="text-xs text-slate-400 max-w-xs truncate" title="{{ $plan->description }}">{{ $plan->description }}</div>
                                    </td>

                                    <!-- Tier -->
                                    <td class="py-4 font-extrabold text-slate-300 uppercase tracking-wider text-xs">
                                        {{ $plan->tier }}
                                    </td>

                                    <!-- Price -->
                                    <td class="py-4 font-bold text-white">
                                        ${{ number_format($plan->price, 2) }}
                                    </td>

                                    <!-- Target Return -->
                                    <td class="py-4 font-extrabold text-emerald-400">
                                        ${{ number_format($plan->target_return, 2) }}
                                    </td>

                                    <!-- Duration -->
                                    <td class="py-4 text-slate-300">
                                        3-6 Hours <span class="text-xs text-slate-500">(Random)</span>
                                    </td>

                                    <!-- Status -->
                                    <td class="py-4">
                                        @if($plan->status)
                                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-emerald-950/80 border border-emerald-800/80 text-emerald-400">
                                                Active
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-red-950/80 border border-red-800/80 text-red-400">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-4 text-right pr-2">
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- Edit -->
                                            <a href="{{ route('admin.plans.edit', $plan->id) }}" class="text-xs font-semibold bg-slate-800 border border-slate-700 hover:border-slate-600 text-slate-200 px-3 py-1.5 rounded-lg transition">
                                                Edit
                                            </a>

                                            <!-- Delete -->
                                            <form method="POST" action="{{ route('admin.plans.destroy', $plan->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this plan?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs font-semibold bg-red-950/40 border border-red-900/50 hover:bg-red-900 text-red-300 hover:text-white px-2.5 py-1.5 rounded-lg transition">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
