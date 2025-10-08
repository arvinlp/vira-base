<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'features',
        'is_demo',
        'is_free',
        'status',
        'max_users',
        'max_storage_mb',
    ];

    /**
     * Cast fields to proper types.
     */
    protected $casts = [
        'price' => 'array',
        'features' => 'array',
        'is_demo' => 'boolean',
        'is_free' => 'boolean',
        'max_users' => 'integer',
        'max_storage_mb' => 'integer',
    ];

    /**
     * Accessor to ensure empty arrays if null.
     */
    protected function price(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn($value) => $value ? json_decode($value, true) : [],
            set: fn($value) => json_encode($value)
        );
    }

    protected function features(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn($value) => $value ? json_decode($value, true) : [],
            set: fn($value) => json_encode($value)
        );
    }

    /**
     * Scope for active plans.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Relation with tenant_plans table (if applicable).
     */
    public function tenantPlans()
    {
        return $this->hasMany(TenantPlan::class);
    }
}
