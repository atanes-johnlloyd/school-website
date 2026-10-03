<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
class Room extends Model
{
    use Auditable;
    protected $fillable = ['code', 'name', 'building', 'floor', 'capacity', 'type', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    public function schedules() { return $this->hasMany(ClassSchedule::class); }
}