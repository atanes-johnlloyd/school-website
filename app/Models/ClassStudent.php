<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassStudent extends Model
{
    protected $fillable = ['class_id', 'student_id', 'status', 'enrolled_at'];

    protected $casts = ['enrolled_at' => 'datetime'];

    public function classroom()   { return $this->belongsTo(ClassRoom::class, 'class_id'); }
    public function student() { return $this->belongsTo(Student::class); }
}