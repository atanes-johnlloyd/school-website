<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = [
        'quiz_id', 'student_id', 'attempt_number',
        'started_at', 'submitted_at', 'score', 'status',
        'expires_at',
        'warning_count',
        'questions_order',
        'options_order',
        'submitted_reason',
    ];

    protected $casts = [
        'started_at'   => 'datetime',
        'submitted_at' => 'datetime',
        'score'        => 'decimal:2',
        'expires_at'       => 'datetime',
        'questions_order'  => 'array',
        'options_order'    => 'array',
    ];

    public function quiz()    { return $this->belongsTo(Quiz::class); }
    public function student() { return $this->belongsTo(Student::class); }
    public function answers() { return $this->hasMany(QuizAnswer::class); }
}