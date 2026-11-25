<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('tenant_id', auth()->user()->tenant_id)
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Security check
        if ($order->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $order->load(['items', 'allocations.unit']);

        return view('admin.orders.show', compact('order'));
    }

    public function create()
    {
        return view('admin.orders.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:150',
            'quantity' => 'required|integer|min:1',
            'deadline' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string',
        ]);

        $order = Order::create([
            'tenant_id' => auth()->user()->tenant_id,
            'order_number' => Order::generateOrderNumber(),
            'status' => 'confirmed',
            'deadline' => $validated['deadline'],
            'notes' => $validated['notes'] ?? null,
            'total_amount' => 0,
        ]);

        // Order item
        $order->items()->create([
            'product_name' => $validated['product_name'],
            'quantity' => $validated['quantity'],
            'unit_price' => 0,
            'total_price' => 0,
        ]);

        return redirect()->route('allocations.scorecard', $order)
            ->with('success', 'Order created. View scorecard to allocate.');
    }

    public function edit(Order $order)
    {
        if ($order->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }
        return view('admin.orders.edit', compact('order'));
    }

    public function update(\Illuminate\Http\Request $request, Order $order)
    {
        if ($order->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:draft,quoted,confirmed,allocated,in_production,qc,ready,delivered,cancelled',
            'deadline' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $order->update($validated);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order updated.');
    }

    public function destroy(Order $order)
    {
        if ($order->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted.');
    }
}