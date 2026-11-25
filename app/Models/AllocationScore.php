<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AllocationScore extends Model
{
    protected $fillable = [
        'allocation_id', 'factor', 'weight', 'raw_score', 'weighted_score',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'raw_score' => 'decimal:2',
            'weighted_score' => 'decimal:2',
        ];
    }

    public function allocation(): BelongsTo
    {
        return $this->belongsTo(Allocation::class);
    }
}