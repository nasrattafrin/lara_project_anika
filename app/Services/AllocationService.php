<?php

namespace App\Services;

use App\Models\Allocation;
use App\Models\AllocationScore;
use App\Models\Machine;
use App\Models\Order;
use App\Models\ProductionJob;
use App\Models\Unit;

class AllocationService
{
    /**
     * Weight configuration (documentation Section 08)
     */
    private const WEIGHTS = [
        'on_time'  => 30,
        'workload' => 25,
        'cost'     => 20,
        'history'  => 15,
        'quality'  => 10,
    ];

    
    private array $ineligible = [];

    public function ineligibleUnits(): array
    {
        return $this->ineligible;
    }

    /**
     * Calculate scores for eligible units for a given order
     */
    public function calculateScores(Order $order): array
    {
        $this->ineligible = [];
        $order->loadMissing('items');

        $quantity = max(1, (int) $order->items->sum('quantity'));
        $requiredTypes = $order->items->pluck('process_type')->filter()->unique()->values()->all();

        // Purano suggested allocation delete
        Allocation::where('order_id', $order->id)->where('status', 'suggested')->delete();

        $units = Unit::where('tenant_id', $order->tenant_id)
            ->where('status', 'active')
            ->with(['machines' => fn ($q) => $q->where('status', 'active')])
            ->get();

        // ---------- Pass 1: eligibility + raw numbers ----------
        $candidates = [];

        foreach ($units as $unit) {
            $machines = $unit->machines;

            if ($machines->isEmpty()) {
                $this->ineligible[] = "{$unit->name}: no active machine";
                continue;
            }

            
            $missing = collect($requiredTypes)
                ->filter(fn ($t) => $machines->where('process_type', $t)->isEmpty());

            if ($missing->isNotEmpty()) {
                $labels = $missing->map(fn ($t) => Machine::TYPES[$t] ?? $t)->implode(', ');
                $this->ineligible[] = "{$unit->name}: {$labels} missing required machine";
                continue;
            }

            $matching = empty($requiredTypes)
                ? $machines
                : $machines->whereIn('process_type', $requiredTypes);

            $dailyCapacity = (int) $matching->sum('capacity_per_day');

            if ($dailyCapacity <= 0) {
                $this->ineligible[] = "{$unit->name}:  zero daily capacity";
                continue;
            }

           
            $backlog = (int) ProductionJob::where('unit_id', $unit->id)
                ->whereIn('status', ['queued', 'printing'])
                ->sum('quantity');

          
            $costPerPiece = (float) $matching->min('cost_per_unit');

            $candidates[] = [
                'unit'     => $unit,
                'capacity' => $dailyCapacity,
                'backlog'  => $backlog,
                'cost'     => $costPerPiece * $quantity,
            ];
        }

        if (empty($candidates)) {
            return []; 
        }

        $minCost = collect($candidates)->min('cost');

        // ---------- Pass 2: scoring ----------
        $results = [];

        foreach ($candidates as $c) {
            $unit = $c['unit'];

            $onTime   = $this->onTimeScore($order, $quantity, $c['capacity'], $c['backlog']);
            $workload = $this->workloadScore($c['capacity'], $c['backlog']);
            $cost     = $this->costScore($c['cost'], $minCost);
            $history  = $this->historyScore($unit);
            $quality  = $this->qualityScore($unit);

            $total = (
                ($onTime * self::WEIGHTS['on_time']) +
                ($workload * self::WEIGHTS['workload']) +
                ($cost * self::WEIGHTS['cost']) +
                ($history * self::WEIGHTS['history']) +
                ($quality * self::WEIGHTS['quality'])
            ) / 100;

            $allocation = Allocation::create([
                'order_id' => $order->id,
                'unit_id'  => $unit->id,
                'status'   => 'suggested',
                'score'    => round($total, 2),
            ]);

            $breakdown = [
                'on_time'  => $onTime,
                'workload' => $workload,
                'cost'     => $cost,
                'history'  => $history,
                'quality'  => $quality,
            ];

            foreach ($breakdown as $factor => $raw) {
                AllocationScore::create([
                    'allocation_id'  => $allocation->id,
                    'factor'         => $factor,
                    'weight'         => self::WEIGHTS[$factor],
                    'raw_score'      => round($raw, 2),
                    'weighted_score' => round($raw * self::WEIGHTS[$factor] / 100, 2),
                ]);
            }

            $allocation->load(['unit', 'scores']);
            $results[] = $allocation;
        }

        usort($results, fn ($a, $b) => $b->score <=> $a->score);

        return $results;
    }

    private function onTimeScore(Order $order, int $quantity, int $capacity, int $backlog): float
    {
        if (!$order->deadline) {
            return 100;
        }

        $daysAvailable = (int) now()->startOfDay()
            ->diffInDays($order->deadline->copy()->startOfDay(), false);

        if ($daysAvailable <= 0) {
            return 0;
        }

        $daysNeeded = max(1, (int) ceil(($backlog + $quantity) / $capacity));

        if ($daysNeeded > $daysAvailable) {
            return max(0, 100 - (($daysNeeded - $daysAvailable) * 20));
        }

        return min(100, 50 + (($daysAvailable - $daysNeeded) * 10));
    }

    
    private function workloadScore(int $capacity, int $backlog): float
    {
        $weeklyCapacity = $capacity * 7;
        $utilization = min(1, $backlog / $weeklyCapacity);

        return (1 - $utilization) * 100;
    }

    
    private function costScore(float $unitCost, float $minCost): float
    {
        if ($unitCost <= 0 || $minCost <= 0) {
            return 100;
        }

        return min(100, ($minCost / $unitCost) * 100);
    }

    
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
            ->whereRaw('DATE(completed_at) <= deadline')
            ->count();

        return ($onTimeJobs / $totalJobs) * 100;
    }

    
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

        return (1 - ($reworkedJobs / $totalJobs)) * 100;
    }
}