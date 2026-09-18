<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class AssignmentSubmission extends Model
{
    use Auditable;
    protected $fillable = [
        'assignment_id', 'student_id', 'submitted_at', 'file_path',
        'text_content', 'status', 'grade', 'feedback',
        'graded_by', 'graded_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'graded_at'    => 'datetime',
        'grade'        => 'decimal:2',
    ];

    public function assignment() { return $this->belongsTo(Assignment::class); }
    public function student()    { return $this->belongsTo(Student::class); }
    public function grader()     { return $this->belongsTo(User::class, 'graded_by'); }
}