<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Allocation;
use App\Models\Order;
use App\Services\AllocationService;
use Illuminate\Http\Request;

class AllocationController extends Controller
{
    public function __construct(
        private AllocationService $allocationService
    ) {}

    /**
     * Order er scorecard dekhaw
     */
   public function scorecard(Order $order)

    {
        $allocations = $this->allocationService->calculateScores($order);

     
        $recommended = $allocations[0] ?? null;

        return view('admin.allocations.scorecard', [
            'order' => $order,
            'allocations' => $allocations,
            'recommended' => $recommended,
        ]);
    }
    /**
     * Selected unit e approve
     */
    public function approve(Request $request, Order $order)
    {
        $validated = $request->validate([
            'allocation_id' => 'required|exists:allocations,id',
            'override_reason' => 'nullable|string|max:500',
        ]);

        $allocation = Allocation::findOrFail($validated['allocation_id']);
        $recommended = Allocation::where('order_id', $order->id)
            ->orderByDesc('score')->first();

        // Check if override
        $isOverride = $recommended && $allocation->id !== $recommended->id;

        if ($isOverride && empty($validated['override_reason'])) {
            return back()->with('error', 'Override korle reason dite hobe.');
        }

        // Approve selected
        $allocation->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'override_reason' => $validated['override_reason'] ?? null,
            'approved_at' => now(),
        ]);

        // Reject others
        Allocation::where('order_id', $order->id)
            ->where('id', '!=', $allocation->id)
            ->update(['status' => 'rejected']);

        // Order status update
        $order->update(['status' => 'allocated']);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Unit allocated. Order status: allocated.');
    }

    /**
     * Score breakdown details
     */
    public function show(Allocation $allocation)
    {
        $allocation->load(['order', 'unit', 'scores', 'approvedBy']);
        return view('admin.allocations.show', compact('allocation'));
    }
}