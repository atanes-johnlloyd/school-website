<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id', 'school_year_id', 'section_id',
        'status', 'enrolled_at', 'enrolled_by',
    ];

    protected $casts = ['enrolled_at' => 'datetime'];

    public function student()     { return $this->belongsTo(Student::class); }
    public function schoolYear()  { return $this->belongsTo(SchoolYear::class); }
    public function section()     { return $this->belongsTo(Section::class); }
    public function enrolledBy()  { return $this->belongsTo(User::class, 'enrolled_by'); }
}