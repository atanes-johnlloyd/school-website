<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassModule extends Model
{
    protected $fillable = ['class_id', 'title', 'description', 'position', 'is_published'];
    protected $casts    = ['is_published' => 'boolean'];

    public function classroom()       { return $this->belongsTo(ClassRoom::class, 'class_id'); }
    public function lessons()     { return $this->hasMany(Lesson::class); }
    public function assignments() { return $this->hasMany(Assignment::class); }
    public function quizzes()     { return $this->hasMany(Quiz::class); }
}