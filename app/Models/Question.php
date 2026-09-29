<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    public const TYPES = ['multiple_choice', 'true_false', 'short_answer', 'essay'];

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'category',
        'type',
        'question_text',
        'points',
        'explanation',
    ];

    protected $casts = [
        'points' => 'decimal:2',
    ];

    // ─── Relationships ─────────────────────────────────
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class)->orderBy('position');
    }

    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, 'quiz_questions')
                    ->withPivot('position', 'points_override')
                    ->withTimestamps();
    }
}