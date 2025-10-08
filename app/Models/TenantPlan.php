<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantPlan extends Model
{
    use HasFactory;
    protected $fillable = [
        'tenant_id',
        'plan_id',
        'starts_at',
        'ends_at',
        'is_active',
    ];
}
