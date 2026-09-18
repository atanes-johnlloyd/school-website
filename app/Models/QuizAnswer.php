<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    protected $fillable = [
        'quiz_attempt_id', 'question_id', 'question_option_id',
        'answer_text', 'is_correct', 'points_awarded',
    ];

    protected $casts = [
        'is_correct'     => 'boolean',
        'points_awarded' => 'decimal:2',
    ];

    public function attempt()  { return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id'); }
    public function question() { return $this->belongsTo(Question::class); }
    public function option()   { return $this->belongsTo(QuestionOption::class, 'question_option_id'); }
}