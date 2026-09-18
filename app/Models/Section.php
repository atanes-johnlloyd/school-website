<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_year_id', 'strand_id', 'grade_level',
        'name', 'adviser_id', 'max_capacity',
    ];

    public function schoolYear()  { return $this->belongsTo(SchoolYear::class); }
    public function strand()      { return $this->belongsTo(Strand::class); }
    public function adviser()     { return $this->belongsTo(Teacher::class, 'adviser_id'); }
    public function classroom()     { return $this->hasMany(ClassRoom::class, 'section_id'); }
    public function enrollments() { return $this->hasMany(Enrollment::class); }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments', 'section_id', 'student_id')
                    ->withPivot('status', 'enrolled_at')
                    ->withTimestamps();
    }
}