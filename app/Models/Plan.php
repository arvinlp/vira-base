<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'features' => 'array',
        'price' => 'array',
        'is_demo' => 'boolean',
        'is_free' => 'boolean',
        'status' => 'string',
    ];
}
