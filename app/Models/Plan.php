<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
class Plan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'price',
        'currency',
        'billing_cycle',
        'limits',
        'features',
        'trial_days',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'limits' => 'array',       
            'features' => 'array',     
            'is_active' => 'boolean',
            'trial_days' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    // ── Relationships ──

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    // ── Helper methods ──
    public function hasLimit(string $key): bool
    {
        return isset($this->limits[$key]);
    }

    /**
     * Specific limit  value return
     */
    public function getLimit(string $key, $default = null)
    {
        return $this->limits[$key] ?? $default;
    }

    /**
     * Feature enable check
  
     */
    public function hasFeature(string $key): bool
    {
        return (bool) ($this->features[$key] ?? false);
    }

    /**
     * Free trial check
     */
    public function hasTrial(): bool
    {
        return $this->trial_days > 0;
    }

    
    public function isActive(): bool
    {
        return $this->is_active === true;
    }
}