<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'student_id',
        'attendance_date',
        'status',
        'notes',
        'marked_by',
    ];

    protected $casts = [
        'attendance_date' => 'date:Y-m-d',
    ];

    public const STATUSES = ['present', 'absent', 'late', 'excused'];

    // ─── Relationships ─────────────────────────────────
    public function classroom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    // ─── Scopes ────────────────────────────────────────
    public function scopeForDate(Builder $q, string $date): Builder
    {
        return $q->whereDate('attendance_date', $date);
    }

    public function scopeForClass(Builder $q, int $classId): Builder
    {
        return $q->where('class_id', $classId);
    }

    public function scopeForStudent(Builder $q, int $studentId): Builder
    {
        return $q->where('student_id', $studentId);
    }
}