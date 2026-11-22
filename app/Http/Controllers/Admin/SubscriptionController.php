<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['tenant', 'plan'])->latest()->paginate(15);
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function create(Request $request)
    {
        $tenants = Tenant::orderBy('name')->get();
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        $selectedTenant = $request->query('tenant_id');
        return view('admin.subscriptions.create', compact('tenants', 'plans', 'selectedTenant'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'plan_id' => 'required|exists:plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'auto_renew' => 'nullable|boolean',
        ]);

        $plan = Plan::findOrFail($validated['plan_id']);
        $tenant = Tenant::findOrFail($validated['tenant_id']);

        $endsAt = $validated['billing_cycle'] === 'monthly'
            ? now()->addMonth()
            : now()->addYear();

        Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => $endsAt,
            'amount' => $plan->price,
            'currency' => $plan->currency,
            'billing_cycle' => $validated['billing_cycle'],
            'auto_renew' => $request->boolean('auto_renew', true),
        ]);

        $tenant->update(['plan_id' => $plan->id]);

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription created successfully!');
    }

    public function show(Subscription $subscription)
    {
        $subscription->load(['tenant', 'plan', 'invoices']);
        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function cancel(Subscription $subscription)
    {
        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'auto_renew' => false,
        ]);

        return back()->with('success', 'Subscription cancelled.');
    }
}