<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionJob extends Model
{
    protected $fillable = [
        'unit_id', 'order_id', 'job_number', 'quantity',
        'status', 'rework_count', 'completed_at', 'deadline',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'deadline' => 'date',
        ];
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}