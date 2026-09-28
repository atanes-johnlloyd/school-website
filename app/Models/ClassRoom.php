<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassRoom extends Model
{
    use SoftDeletes;
    use HasFactory;
    
    protected $table = 'classes';

    protected $fillable = [
        'subject_id', 'section_id', 'teacher_id', 'term_id',
        'weight_written_work', 'weight_performance_task',
        'weight_quarterly_exam', 'is_published',
    ];

    protected $casts = [
        'weight_written_work'     => 'decimal:2',
        'weight_performance_task' => 'decimal:2',
        'weight_quarterly_exam'   => 'decimal:2',
        'is_published'            => 'boolean',
    ];

    public function subject()      { return $this->belongsTo(Subject::class); }
    public function section()      { return $this->belongsTo(Section::class); }
    public function teacher()      { return $this->belongsTo(Teacher::class); }
    public function term()         { return $this->belongsTo(Term::class); }

    public function schedules()    { return $this->hasMany(ClassSchedule::class, 'class_id'); }
    public function modules()      { return $this->hasMany(ClassModule::class, 'class_id'); }
    public function lessons()      { return $this->hasMany(Lesson::class, 'class_id'); }
    public function assignments()  { return $this->hasMany(Assignment::class, 'class_id'); }
    public function quizzes()      { return $this->hasMany(Quiz::class, 'class_id'); }
    public function grades()       { return $this->hasMany(Grade::class, 'class_id'); }
    public function announcements(){ return $this->hasMany(Announcement::class, 'class_id'); }
    
    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_students', 'class_id', 'student_id')
                    ->withPivot('status', 'enrolled_at')
                    ->withTimestamps();
    }

    public function isTaughtBy(?User $user): bool
    {
        if (! $user || ! $user->teacher) {
            return false;
        }

        return $this->teacher_id === $user->teacher->id;
    }

    public function hasStudent(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return \DB::table('class_students')
            ->join('students', 'students.id', '=', 'class_students.student_id')
            ->where('class_students.class_id', $this->id)
            ->where('students.user_id', $user->id)
            ->exists();
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class, 'class_id');
    }
}
