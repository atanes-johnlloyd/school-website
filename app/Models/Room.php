<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['code', 'name', 'building', 'floor', 'capacity', 'type', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    public function schedules() { return $this->hasMany(ClassSchedule::class); }
}