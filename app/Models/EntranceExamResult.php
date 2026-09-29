<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntranceExamResult extends Model
{
    protected $fillable = [
        'entrance_exam_id', 'applicant_id', 'score',
        'result', 'remarks', 'recorded_by', 'recorded_at',
    ];

    protected $casts = [
        'score'       => 'decimal:2',
        'recorded_at' => 'datetime',
    ];

    public function exam()      { return $this->belongsTo(EntranceExam::class, 'entrance_exam_id'); }
    public function applicant() { return $this->belongsTo(Applicant::class); }
    public function recorder()  { return $this->belongsTo(User::class, 'recorded_by'); }
}