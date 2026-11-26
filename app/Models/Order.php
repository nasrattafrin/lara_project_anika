<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Concerns\BelongsToTenant;

class Order extends Model
{
    use SoftDeletes,BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'customer_id', 'order_number', 'status',
        'total_amount', 'deadline', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'total_amount' => 'decimal:2',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(ProductionJob::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(Allocation::class);
    }

            public static function generateOrderNumber(): string
    {
        $year = now()->year;
        $count = self::withoutGlobalScopes()->withTrashed()
            ->whereYear('created_at', $year)->count() + 1;

        return 'ORD-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}