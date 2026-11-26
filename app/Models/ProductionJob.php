<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionJob extends Model
{
    // Kon stage er pore kon stage
    public const FLOW = [
        'queued'   => 'printing',
        'printing' => 'qc',
        'qc'       => 'ready',
        'ready'    => 'delivered',
    ];

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

    public function logs(): HasMany
    {
        return $this->hasMany(JobStatusLog::class)->latest();
    }

    public function nextStatus(): ?string
    {
        return self::FLOW[$this->status] ?? null;
    }

    public static function generateJobNumber(): string
    {
        $year = now()->year;
        $count = self::whereYear('created_at', $year)->count() + 1;

        return 'JOB-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}