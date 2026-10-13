<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    protected $fillable = ['class_id', 'room_id', 'day_of_week', 'time_start', 'time_end'];

    public function classroom() { return $this->belongsTo(ClassRoom::class, 'class_id'); }
    public function room()      { return $this->belongsTo(Room::class); }

    /**
     * MySQL TIME columns come back as "HH:MM:SS". The rest of the app
     * (schedule templates, Vue grid matching) uses "HH:MM". Trim on read
     * so comparisons and JSON payloads stay consistent.
     */
    public function getTimeStartAttribute($value): ?string
    {
        if ($value === null || $value === '') return null;
        return substr($value, 0, 5);
    }

    public function getTimeEndAttribute($value): ?string
    {
        if ($value === null || $value === '') return null;
        return substr($value, 0, 5);
    }
}