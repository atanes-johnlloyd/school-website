<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Grade extends Model
{
    use Auditable;
    
    protected $fillable = [
        'class_id', 'student_id',
        'written_work_score', 'performance_task_score', 'quarterly_exam_score',
        'final_grade', 'remarks', 'is_finalized', 'finalized_by', 'finalized_at',
    ];

    protected $casts = [
        'written_work_score'     => 'decimal:2',
        'performance_task_score' => 'decimal:2',
        'quarterly_exam_score'   => 'decimal:2',
        'final_grade'            => 'decimal:2',
        'is_finalized'           => 'boolean',
        'finalized_at'           => 'datetime',
    ];

    public function classroom()      { return $this->belongsTo(ClassRoom::class, 'class_id'); }
    public function student()    { return $this->belongsTo(Student::class); }
    public function finalizer()  { return $this->belongsTo(User::class, 'finalized_by'); }
}