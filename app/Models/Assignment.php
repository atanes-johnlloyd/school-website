<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'class_id', 'class_module_id', 'title', 'instructions',
        'due_at', 'points', 'allow_late', 'is_published',
    ];

    protected $casts = [
        'due_at'       => 'datetime',
        'points'       => 'decimal:2',
        'allow_late'   => 'boolean',
        'is_published' => 'boolean',
    ];

    public function classroom()       { return $this->belongsTo(ClassRoom::class, 'class_id'); }
    public function module()      { return $this->belongsTo(ClassModule::class, 'class_module_id'); }
    public function submissions() { return $this->hasMany(AssignmentSubmission::class); }
}