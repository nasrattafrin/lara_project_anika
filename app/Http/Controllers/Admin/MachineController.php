<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MachineController extends Controller
{
    public function index()
    {
        $machines = Machine::with('unit')->orderBy('unit_id')->orderBy('name')->paginate(15);

        return view('admin.machines.index', compact('machines'));
    }

    public function create()
    {
        $units = Unit::orderBy('name')->get();

        return view('admin.machines.create', compact('units'));
    }

    public function store(Request $request)
    {
        Machine::create($this->validated($request));

        return redirect()->route('machines.index')->with('success', 'Machine added.');
    }

    public function edit(Machine $machine)
    {
        $units = Unit::orderBy('name')->get();

        return view('admin.machines.edit', compact('machine', 'units'));
    }

    public function update(Request $request, Machine $machine)
    {
        $machine->update($this->validated($request));

        return redirect()->route('machines.index')->with('success', 'Machine updated.');
    }

    public function destroy(Machine $machine)
    {
        $machine->delete();

        return redirect()->route('machines.index')->with('success', 'Machine deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'unit_id' => [
                'required',
                Rule::exists('units', 'id')
                    ->where('tenant_id', auth()->user()->tenant_id)
                    ->whereNull('deleted_at'),
            ],
            'name'             => 'required|string|max:100',
            'process_type'     => ['required', Rule::in(array_keys(Machine::TYPES))],
            'capacity_per_day' => 'required|integer|min:1',
            'cost_per_unit'    => 'required|numeric|min:0',
            'status'           => 'required|in:active,maintenance,inactive',
        ]);
    }
}