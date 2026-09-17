<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdminPosition extends Model
{
    protected $fillable = [
        'name',
        'description',
        'default_permissions',
        'is_active',
    ];

    protected $casts = [
        'default_permissions' => 'array',
        'is_active' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
