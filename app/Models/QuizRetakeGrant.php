<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizRetakeGrant extends Model
{
    protected $fillable = [
        'quiz_id', 'student_id', 'granted_by',
        'reason', 'granted_at', 'used_at',
    ];

    protected $casts = [
        'granted_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    public function quiz()    { return $this->belongsTo(Quiz::class); }
    public function student() { return $this->belongsTo(Student::class); }
    public function granter() { return $this->belongsTo(User::class, 'granted_by'); }

    public function scopeUnused($q) { return $q->whereNull('used_at'); }
}