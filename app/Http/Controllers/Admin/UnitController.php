<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Services\PlanLimiter;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    // ─────────────────────────────────────────────────────────
    // PRESS RESPONSIBLE — sob unit
    // ─────────────────────────────────────────────────────────
    public function index()
    {
        $tenant = auth()->user()->tenant;

        if (!$tenant) {
            return redirect()->route('dashboard')
                ->with('error', 'No tenant assigned to your account.');
        }

        $units = Unit::where('tenant_id', $tenant->id)->orderBy('name')->paginate(15);
        $limitCheck = PlanLimiter::check($tenant, 'units');

        return view('admin.units.index', compact('units', 'limitCheck'));
    }

    public function create()
    {
        $tenant = auth()->user()->tenant;
        $limitCheck = PlanLimiter::check($tenant, 'units');

        if (!$limitCheck['allowed']) {
            return redirect()->route('units.index')->with('error', $limitCheck['message']);
        }

        return view('admin.units.create');
    }

    public function store(Request $request)
    {
        $tenant = auth()->user()->tenant;
        $limitCheck = PlanLimiter::check($tenant, 'units');

        if (!$limitCheck['allowed']) {
            return back()->with('error', $limitCheck['message']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:units,name,NULL,id,tenant_id,' . $tenant->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,maintenance',
        ]);

        $validated['tenant_id'] = $tenant->id;
        Unit::create($validated);

        return redirect()->route('units.index')->with('success', 'Unit created successfully!');
    }

    public function show(Unit $unit)
    {
        $tenant = auth()->user()->tenant;

        if ($unit->tenant_id !== $tenant->id) {
            abort(403);
        }

        return view('admin.units.show', compact('unit'));
    }

    public function edit(Unit $unit)
    {
        $tenant = auth()->user()->tenant;

        if ($unit->tenant_id !== $tenant->id) {
            abort(403);
        }

        return view('admin.units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $tenant = auth()->user()->tenant;

        if ($unit->tenant_id !== $tenant->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:units,name,' . $unit->id . ',id,tenant_id,' . $tenant->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,maintenance',
        ]);

        $unit->update($validated);

        return redirect()->route('units.index')->with('success', 'Unit updated successfully!');
    }

    public function destroy(Unit $unit)
    {
        $tenant = auth()->user()->tenant;

        if ($unit->tenant_id !== $tenant->id) {
            abort(403);
        }

        $unit->delete();

        return redirect()->route('units.index')->with('success', 'Unit deleted successfully!');
    }

    
    public function myUnit()
    {
        $user = auth()->user();
        $unit = $user->unit;

        if (!$unit) {
            return redirect()->route('dashboard')
                ->with('error', 'No unit assigned to your account.');
        }

        return view('admin.units.my-unit', compact('unit'));
    }
}