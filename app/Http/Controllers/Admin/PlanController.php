<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('sort_order')->paginate(15);
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:plans,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|in:BDT,USD',
            'billing_cycle' => 'required|in:monthly,yearly',
            'limits.units' => 'required|integer|min:1',
            'limits.users' => 'required|integer|min:1',
            'limits.storage_gb' => 'nullable|integer|min:0',
            'limits.orders_per_month' => 'nullable|integer|min:0',
            'trial_days' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['features'] = array_map('boolval', $request->input('features', []));

        Plan::create($validated);

        return redirect()->route('plans.index')->with('success', 'Plan created successfully!');
    }

    public function show(Plan $plan)
    {
        return view('admin.plans.show', compact('plan'));
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:plans,code,' . $plan->id,
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|in:BDT,USD',
            'billing_cycle' => 'required|in:monthly,yearly',
            'limits.units' => 'required|integer|min:1',
            'limits.users' => 'required|integer|min:1',
            'limits.storage_gb' => 'nullable|integer|min:0',
            'limits.orders_per_month' => 'nullable|integer|min:0',
            'trial_days' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['features'] = array_map('boolval', $request->input('features', []));

        $plan->update($validated);

        return redirect()->route('plans.index')->with('success', 'Plan updated successfully!');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return redirect()->route('plans.index')->with('success', 'Plan deleted successfully!');
    }
}