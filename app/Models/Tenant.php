<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'slug',
        'name',
        'email',
        'phone',
        'plan_id',
        'status',
        'trial_ends_at',
        'suspended_at',
        'suspension_reason',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'trial_ends_at' => 'datetime',
            'suspended_at' => 'datetime',
        ];
    }

    

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isTrial(): bool
    {
        return $this->status === 'trial';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    

    
    public function onTrial(): bool
    {
        return $this->isTrial()
            && $this->trial_ends_at
            && $this->trial_ends_at->isFuture();
    }

    
    public function trialExpired(): bool
    {
        return $this->isTrial()
            && $this->trial_ends_at
            && $this->trial_ends_at->isPast();
    }

    
    public function trialDaysLeft(): int
    {
        if (!$this->trial_ends_at || !$this->trial_ends_at->isFuture()) {
            return 0;
        }
        return (int) now()->diffInDays($this->trial_ends_at, false);
    }

    
    public function expireTrial(): void
    {
        if ($this->trialExpired()) {
            $this->update(['status' => 'expired']);
        }
    }

    

    /**
     * Plan limit check 
     *
     * @param string $key      ('units', 'users', 'orders_per_month', 'storage_gb')
     * @param int $current     Current usage
     * @return array           ['allowed', 'limit', 'current', 'remaining', 'message']
     */
    public function checkLimit(string $key, int $current = 0): array
    {
        // Plan nei
        if (!$this->plan) {
            return [
                'allowed' => false,
                'limit' => 0,
                'current' => $current,
                'remaining' => 0,
                'message' => 'No plan assigned. Please contact support.',
            ];
        }

        $limit = $this->plan->getLimit($key);

        // Limit null = unlimited
        if ($limit === null) {
            return [
                'allowed' => true,
                'limit' => null,
                'current' => $current,
                'remaining' => null,
                'message' => 'Unlimited',
            ];
        }

        $allowed = $current < $limit;
        $remaining = max(0, $limit - $current);

        return [
            'allowed' => $allowed,
            'limit' => $limit,
            'current' => $current,
            'remaining' => $remaining,
            'message' => $allowed
                ? "OK — {$current}/{$limit} used"
                : "Limit reached — {$current}/{$limit}. Please upgrade your plan.",
        ];
    }

    
    public function hasReachedLimit(string $key, int $current = 0): bool
    {
        return !$this->checkLimit($key, $current)['allowed'];
    }

    
    public function hasFeature(string $feature): bool
    {
        return $this->plan && $this->plan->hasFeature($feature);
    }

    
    public function suspend(string $reason = null): void
    {
        $this->update([
            'status' => 'suspended',
            'suspended_at' => now(),
            'suspension_reason' => $reason,
        ]);
    }

    
    public function reactivate(): void
    {
        $this->update([
            'status' => 'active',
            'suspended_at' => null,
            'suspension_reason' => null,
        ]);
    }

    

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeTrial($query)
    {
        return $query->where('status', 'trial');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    public function scopeWithExpiredTrial($query)
    {
        return $query->where('status', 'trial')
            ->where('trial_ends_at', '<', now());
    }
}