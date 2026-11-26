<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Machine extends Model
{
    use BelongsToTenant;

    public const TYPES = [
        'offset'       => 'Offset Printing',
        'digital'      => 'Digital Printing',
        'large_format' => 'Large Format',
        'screen'       => 'Screen Printing',
    ];

    protected $fillable = [
        'tenant_id', 'unit_id', 'name', 'process_type',
        'capacity_per_day', 'cost_per_unit', 'status',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->process_type] ?? $this->process_type;
    }
}