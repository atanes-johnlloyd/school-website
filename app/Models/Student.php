<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use SoftDeletes;
    use HasFactory;
    
    protected $fillable = [
        'user_id', 'lrn', 'sex', 'date_of_birth', 'contact_number',
        'house_street', 'barangay', 'municipality', 'province', 'zip_code', 'status',
    ];

    protected $casts = ['date_of_birth' => 'date'];

    public function user()              { return $this->belongsTo(User::class); }
    public function enrollments()       { return $this->hasMany(Enrollment::class); }
    public function classroom()           { return $this->belongsToMany(ClassRoom::class, 'class_students', 'student_id', 'class_id')->withPivot('status', 'enrolled_at')->withTimestamps(); }
    public function submissions()       { return $this->hasMany(AssignmentSubmission::class); }
    public function quizAttempts()      { return $this->hasMany(QuizAttempt::class); }
    public function grades()            { return $this->hasMany(Grade::class); }

    public function convertedFromApplicant()
    {
        return $this->hasOne(Applicant::class, 'converted_student_id');
    }
    
    public function quizRetakeGrants()
    {
        return $this->hasMany(QuizRetakeGrant::class);
    }
}