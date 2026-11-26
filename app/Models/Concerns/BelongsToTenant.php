<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        
        static::addGlobalScope('tenant', function (Builder $builder) {
            $user = auth()->user();

            if ($user && $user->tenant_id) {
                $builder->where(
                    $builder->getModel()->getTable() . '.tenant_id',
                    $user->tenant_id
                );
            }
        });

        
        static::creating(function ($model) {
            if (empty($model->tenant_id) && auth()->check() && auth()->user()->tenant_id) {
                $model->tenant_id = auth()->user()->tenant_id;
            }
        });
    }
}