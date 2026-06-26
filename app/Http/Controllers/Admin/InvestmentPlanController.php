<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestmentPlan;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InvestmentPlanController extends Controller
{
    /**
     * Display a listing of the plans.
     */
    public function index(): View
    {
        $plans = InvestmentPlan::orderBy('sort_order', 'asc')->get();
        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new plan.
     */
    public function create(): View
    {
        return view('admin.plans.create');
    }

    /**
     * Store a newly created plan in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'tier' => ['required', 'in:BASIC,GOLD,DIAMOND,VVIP'],
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'target_return' => ['required', 'numeric', 'min:0'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'sort_order' => ['required', 'integer'],
        ]);

        InvestmentPlan::create([
            'tier' => $request->tier,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'target_return' => $request->target_return,
            'duration_days' => $request->duration_days,
            'sort_order' => $request->sort_order,
            'status' => $request->has('status') ? (bool)$request->status : true,
        ]);

        return redirect()->route('admin.plans.index')->with('status', 'Investment plan created successfully.');
    }

    /**
     * Show the form for editing the plan.
     */
    public function edit($id): View
    {
        $plan = InvestmentPlan::findOrFail($id);
        return view('admin.plans.edit', compact('plan'));
    }

    /**
     * Update the plan.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $plan = InvestmentPlan::findOrFail($id);

        $request->validate([
            'tier' => ['required', 'in:BASIC,GOLD,DIAMOND,VVIP'],
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'target_return' => ['required', 'numeric', 'min:0'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'sort_order' => ['required', 'integer'],
        ]);

        $plan->update([
            'tier' => $request->tier,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'target_return' => $request->target_return,
            'duration_days' => $request->duration_days,
            'sort_order' => $request->sort_order,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('admin.plans.index')->with('status', 'Investment plan updated successfully.');
    }

    /**
     * Remove the plan from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $plan = InvestmentPlan::findOrFail($id);
        $plan->delete();

        return redirect()->route('admin.plans.index')->with('status', 'Investment plan deleted successfully.');
    }
}
