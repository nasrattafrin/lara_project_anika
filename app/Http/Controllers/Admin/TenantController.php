<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with('plan')->orderBy('name')->paginate(15);
        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.tenants.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:50|unique:tenants,slug|alpha_dash',
            'name' => 'required|string|max:150',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'plan_id' => 'nullable|exists:plans,id',
            'status' => 'required|in:trial,active,suspended,cancelled,expired',
            'trial_days' => 'nullable|integer|min:0|max:365',
        ]);

        // trial_ends_at calculate
        if ($validated['status'] === 'trial' && !empty($validated['trial_days'])) {
            $validated['trial_ends_at'] = now()->addDays($validated['trial_days']);
        }

        Tenant::create($validated);

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant created successfully!');
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('plan', 'users');
        return view('admin.tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant)
    {
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.tenants.edit', compact('tenant', 'plans'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:50|unique:tenants,slug,' . $tenant->id . '|alpha_dash',
            'name' => 'required|string|max:150',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'plan_id' => 'nullable|exists:plans,id',
            'status' => 'required|in:trial,active,suspended,cancelled,expired',
            'suspension_reason' => 'nullable|string',
        ]);

      
        if ($validated['status'] === 'suspended' && !$tenant->suspended_at) {
            $validated['suspended_at'] = now();
        } elseif ($validated['status'] !== 'suspended') {
            $validated['suspended_at'] = null;
            $validated['suspension_reason'] = null;
        }

        $tenant->update($validated);

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant updated successfully!');
    }

    public function destroy(Tenant $tenant)
    {
   
        if ($tenant->users()->count() > 0) {
            return back()->with('error', 'Cannot delete — tenant has active users.');
        }

        $tenant->delete();

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant deleted successfully!');
    }
}