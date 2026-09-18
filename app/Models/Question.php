<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'teacher_id', 'subject_id', 'type', 'question_text',
        'points', 'explanation',
    ];

    protected $casts = ['points' => 'decimal:2'];

    public function teacher()  { return $this->belongsTo(Teacher::class); }
    public function subject()  { return $this->belongsTo(Subject::class); }
    public function options()  { return $this->hasMany(QuestionOption::class); }
    public function quizzes()  { return $this->belongsToMany(Quiz::class, 'quiz_questions')->withPivot('position', 'points_override')->withTimestamps(); }
}