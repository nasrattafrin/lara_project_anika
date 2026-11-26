<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Allocation;
use App\Models\JobStatusLog;
use App\Models\Order;
use App\Models\ProductionJob;
use App\Services\AllocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AllocationController extends Controller
{
    public function __construct(
        private AllocationService $allocationService
    ) {}

       public function scorecard(Request $request, Order $order)
    {
        if ($order->status === 'allocated') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'This order is already allocated.');
        }

        $existing = Allocation::with(['unit', 'scores'])
            ->where('order_id', $order->id)
            ->where('status', 'suggested')
            ->orderByDesc('score')
            ->get();

        if ($existing->isEmpty() || $request->boolean('refresh')) {
            $allocations = $this->allocationService->calculateScores($order);

            $problems = $this->allocationService->ineligibleUnits();

            if (empty($allocations)) {
                session()->now('error', 'No suitable unit found. ' . implode(' | ', $problems));
            } elseif (!empty($problems)) {
                session()->now('error', 'Ineligible units: ' . implode(' | ', $problems));
            }
        } else {
            $allocations = $existing->all();
        }

        $recommended = $allocations[0] ?? null;

        return view('admin.allocations.scorecard', [
            'order' => $order,
            'allocations' => $allocations,
            'recommended' => $recommended,
        ]);
    }

    /**
     * Selected unit approve
     */
    public function approve(Request $request, Order $order)
    {
        $validated = $request->validate([
            'allocation_id' => 'required|integer',
            'override_reason' => 'nullable|string|max:500',
        ]);

        // Allocation ta obossoi ei order er hote hobe
        $allocation = Allocation::where('order_id', $order->id)
            ->where('id', $validated['allocation_id'])
            ->firstOrFail();

        $recommended = Allocation::where('order_id', $order->id)
            ->orderByDesc('score')->first();

        $isOverride = $recommended && $allocation->id !== $recommended->id;

        if ($isOverride && empty($validated['override_reason'])) {
            return back()->with('error', 'An override reason is required when you select a different unit.');
        }

        DB::transaction(function () use ($allocation, $order, $validated) {
            $allocation->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'override_reason' => $validated['override_reason'] ?? null,
                'approved_at' => now(),
            ]);

            Allocation::where('order_id', $order->id)
                ->where('id', '!=', $allocation->id)
                ->update(['status' => 'rejected']);

            $order->update(['status' => 'allocated']);

            // ===== Step 2: Production Job toiri =====
            $job = ProductionJob::create([
                'unit_id'      => $allocation->unit_id,
                'order_id'     => $order->id,
                'job_number'   => ProductionJob::generateJobNumber(),
                'quantity'     => (int) $order->items()->sum('quantity'),
                'status'       => 'queued',
                'rework_count' => 0,
                'deadline'     => $order->deadline,
            ]);

            JobStatusLog::create([
                'production_job_id' => $job->id,
                'actor_id'          => auth()->id(),
                'from_status'       => null,
                'to_status'         => 'queued',
                'note'              => 'Job created from approved allocation',
            ]);
        });

        return redirect()->route('orders.show', $order)
            ->with('success', 'Unit allocated and production job created.');
    }

    public function show(Allocation $allocation)
    {
        $order = Order::findOrFail($allocation->order_id);

        $allocation->load(['order', 'unit', 'scores', 'approvedBy']);
        return view('admin.allocations.show', compact('allocation'));
    }
}