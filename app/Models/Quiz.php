<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'class_id', 'class_module_id', 'title', 'description', 'instructions',
        'time_limit_minutes', 'attempts_allowed', 'shuffle_questions',
        'passing_score', 'available_from', 'available_until', 'is_published',
        'show_score_immediately',
        'show_correct_answers',
        'show_explanations',
        'shuffle_options',
    ];

    protected $casts = [
        'shuffle_questions' => 'boolean',
        'is_published'      => 'boolean',
        'available_from'    => 'datetime',
        'available_until'   => 'datetime',
        'passing_score'     => 'decimal:2',
        'show_score_immediately' => 'boolean',
        'show_correct_answers'   => 'boolean',
        'show_explanations'      => 'boolean',
        'shuffle_options'        => 'boolean',
    ];

    public function classroom()     { return $this->belongsTo(ClassRoom::class, 'class_id'); }
    public function module()    { return $this->belongsTo(ClassModule::class, 'class_module_id'); }
    public function questions() { return $this->belongsToMany(Question::class, 'quiz_questions')->withPivot('position', 'points_override')->withTimestamps(); }
    public function attempts()  { return $this->hasMany(QuizAttempt::class); }

    public function retakeGrants()
    {
        return $this->hasMany(QuizRetakeGrant::class);
    }

    public function retakeGrantsFor(int $studentId)
    {
        return $this->retakeGrants()->where('student_id', $studentId)->unused();
    }
}