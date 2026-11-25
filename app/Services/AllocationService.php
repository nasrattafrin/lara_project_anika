<?php

namespace App\Services;

use App\Models\Allocation;
use App\Models\AllocationScore;
use App\Models\Order;
use App\Models\ProductionJob;
use App\Models\Unit;

class AllocationService
{
    /**
     * Weight configuration (documentation Section 08)
     */
    private const WEIGHTS = [
        'on_time' => 30,
        'workload' => 25,
        'cost' => 20,
        'history' => 15,
        'quality' => 10,
    ];

    /**
     * Order er jonno sob unit er score calculate kore
     * Returns: allocations sorted by score (highest first)
     */
    public function calculateScores(Order $order): array
    {
        $tenant = $order->tenant;
        $units = Unit::where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->get();

        // Purano suggested allocation delete
        Allocation::where('order_id', $order->id)->where('status', 'suggested')->delete();

        $results = [];

        foreach ($units as $unit) {
            $onTime = $this->onTimeScore($unit, $order);
            $workload = $this->workloadScore($unit);
            $cost = $this->costScore($unit, $order);
            $history = $this->historyScore($unit);
            $quality = $this->qualityScore($unit);

            // Weighted total
            $total = (
                ($onTime * self::WEIGHTS['on_time']) +
                ($workload * self::WEIGHTS['workload']) +
                ($cost * self::WEIGHTS['cost']) +
                ($history * self::WEIGHTS['history']) +
                ($quality * self::WEIGHTS['quality'])
            ) / 100;

            $allocation = Allocation::create([
                'order_id' => $order->id,
                'unit_id' => $unit->id,
                'status' => 'suggested',
                'score' => round($total, 2),
            ]);

            $breakdown = [
                'on_time' => $onTime,
                'workload' => $workload,
                'cost' => $cost,
                'history' => $history,
                'quality' => $quality,
            ];

            foreach ($breakdown as $factor => $raw) {
                AllocationScore::create([
                    'allocation_id' => $allocation->id,
                    'factor' => $factor,
                    'weight' => self::WEIGHTS[$factor],
                    'raw_score' => round($raw, 2),
                    'weighted_score' => round($raw * self::WEIGHTS[$factor] / 100, 2),
                ]);
            }

            $results[] = $allocation;
        }

        // Sort by score descending
        usort($results, fn($a, $b) => $b->score <=> $a->score);

        return $results;
    }

    /**
     * On-time score (weight 30)
     */
    private function onTimeScore(Unit $unit, Order $order): float
    {
        if (!$order->deadline) {
            return 100;
        }

        $daysAvailable = now()->diffInDays($order->deadline, false);
        if ($daysAvailable <= 0) {
            return 0;
        }

        $unitJobs = ProductionJob::where('unit_id', $unit->id)
            ->whereIn('status', ['queued', 'printing'])
            ->count();

        $queueDays = $unitJobs * 2;
        $daysNeeded = $queueDays + 2;

        if ($daysNeeded > $daysAvailable) {
            return max(0, 100 - (($daysNeeded - $daysAvailable) * 20));
        }

        return min(100, ($daysAvailable - $daysNeeded) * 10 + 50);
    }

    /**
     * Workload score (weight 25)
     */
    private function workloadScore(Unit $unit): float
    {
        $activeJobs = ProductionJob::where('unit_id', $unit->id)
            ->whereIn('status', ['queued', 'printing', 'qc'])
            ->count();

        $maxCapacity = 5;
        $utilization = $activeJobs / $maxCapacity;

        return max(0, (1 - $utilization) * 100);
    }

    /**
     * Cost score (weight 20)
     */
    private function costScore(Unit $unit, Order $order): float
    {
        $unitCost = $this->estimateUnitCost($unit, $order);

        $allUnits = Unit::where('tenant_id', $unit->tenant_id)->get();
        $minCost = $allUnits->map(fn($u) => $this->estimateUnitCost($u, $order))->min();

        if ($unitCost == 0) return 100;
        if ($minCost == 0) return 100;

        return min(100, ($minCost / $unitCost) * 100);
    }

    private function estimateUnitCost(Unit $unit, Order $order): float
    {
        return 1000 + ($unit->id * 100);
    }

    /**
     * History score (weight 15)
     */
    private function historyScore(Unit $unit): float
    {
        $totalJobs = ProductionJob::where('unit_id', $unit->id)
            ->where('status', 'delivered')
            ->count();

        if ($totalJobs == 0) {
            return 75;
        }

        $onTimeJobs = ProductionJob::where('unit_id', $unit->id)
            ->where('status', 'delivered')
            ->whereNotNull('completed_at')
            ->whereNotNull('deadline')
            ->whereColumn('completed_at', '<=', 'deadline')
            ->count();

        return ($onTimeJobs / $totalJobs) * 100;
    }

    /**
     * Quality score (weight 10)
     */
    private function qualityScore(Unit $unit): float
    {
        $totalJobs = ProductionJob::where('unit_id', $unit->id)
            ->where('status', 'delivered')
            ->count();

        if ($totalJobs == 0) {
            return 90;
        }

        $reworkedJobs = ProductionJob::where('unit_id', $unit->id)
            ->where('status', 'delivered')
            ->where('rework_count', '>', 0)
            ->count();

        $reworkRate = $reworkedJobs / $totalJobs;
        return (1 - $reworkRate) * 100;
    }
}