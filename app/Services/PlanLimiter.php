<?php

namespace App\Services;

use App\Models\Tenant;

class PlanLimiter
{
    /**
     * Tenant er plan limit check kore
     *
     * @param Tenant $tenant
     * @param string $key      ('units', 'users', 'orders_per_month', 'storage_gb')
     * @param int|null $current Current usage (null = auto detect)
     * @return array
     */
    public static function check(Tenant $tenant, string $key, ?int $current = null): array
    {
        if ($current === null) {
            $current = self::getCurrentUsage($tenant, $key);
        }

        return $tenant->checkLimit($key, $current);
    }

    /**
     * Auto-detect current usage
     */
    public static function getCurrentUsage(Tenant $tenant, string $key): int
    {
        return match ($key) {
            'units' => \App\Models\Unit::where('tenant_id', $tenant->id)->count(),
            'users' => \App\Models\User::where('tenant_id', $tenant->id)->count(),
            default => 0,
        };
    }
}