<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    protected $fillable = ['class_id', 'room_id', 'day_of_week', 'time_start', 'time_end'];

    public function klass() { return $this->belongsTo(ClassRoom::class, 'class_id'); }
    public function room()  { return $this->belongsTo(Room::class); }
}