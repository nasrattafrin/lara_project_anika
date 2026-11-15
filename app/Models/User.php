<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; 

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Role constants
     */
    public const ROLE_PLATFORM_ADMIN     = 'platform_admin';
    public const ROLE_PRESS_RESPONSIBLE  = 'press_responsible';
    public const ROLE_UNIT_MANAGER       = 'unit_manager';
    public const ROLE_ACCOUNTANT         = 'accountant';
    public const ROLE_OPERATOR           = 'operator';
    public const ROLE_CUSTOMER           = 'customer';

    /**
     * Status constants
     */
    public const STATUS_ACTIVE    = 'active';
    public const STATUS_SUSPENDED = 'suspended';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'role',
        'status',
        'unit_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ------------------------------------------------------------------
    // Relationships
    // ------------------------------------------------------------------
  
    
    // public function tenant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    // {
    //     return $this->belongsTo(\App\Models\Tenant::class);
    // }
    //
    // public function unit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    // {
    //     return $this->belongsTo(\App\Models\Unit::class);
    // }

    // ------------------------------------------------------------------
    // Role helpers
    // ------------------------------------------------------------------

    public function isPlatformAdmin(): bool
    {
        return $this->role === self::ROLE_PLATFORM_ADMIN;
    }

    public function isPressResponsible(): bool
    {
        return $this->role === self::ROLE_PRESS_RESPONSIBLE;
    }

    public function isUnitManager(): bool
    {
        return $this->role === self::ROLE_UNIT_MANAGER;
    }

    public function isAccountant(): bool
    {
        return $this->role === self::ROLE_ACCOUNTANT;
    }

    public function isOperator(): bool
    {
        return $this->role === self::ROLE_OPERATOR;
    }

    public function isCustomer(): bool
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    /**
     * Multiple role check shortcut
     */
    public function hasRole(string|array $roles): bool
    {
        $roles = (array) $roles;
        return in_array($this->role, $roles, true);
    }

    // ------------------------------------------------------------------
    // Status helpers
    // ------------------------------------------------------------------

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isSuspended(): bool
    {
        return $this->status === self::STATUS_SUSPENDED;
    }
}