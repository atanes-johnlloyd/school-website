<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $fillable = ['quiz_id', 'question_id', 'position', 'points_override'];
    protected $casts    = ['points_override' => 'decimal:2'];

    public function quiz()     { return $this->belongsTo(Quiz::class); }
    public function question() { return $this->belongsTo(Question::class); }
}