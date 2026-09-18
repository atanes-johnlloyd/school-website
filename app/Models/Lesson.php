<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['class_id', 'class_module_id', 'title', 'body', 'position', 'is_published'];
    protected $casts    = ['is_published' => 'boolean'];

    public function classroom()       { return $this->belongsTo(ClassRoom::class, 'class_id'); }
    public function module()      { return $this->belongsTo(ClassModule::class, 'class_module_id'); }
    public function attachments() { return $this->hasMany(LessonAttachment::class); }
}