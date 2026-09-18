<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'created_by', 'class_id', 'title', 'body',
        'is_pinned', 'published_at', 'expires_at',
    ];

    protected $casts = [
        'is_pinned'    => 'boolean',
        'published_at' => 'datetime',
        'expires_at'   => 'datetime',
    ];

    public function author() { return $this->belongsTo(User::class, 'created_by'); }
    public function classroom()  { return $this->belongsTo(ClassRoom::class, 'class_id'); }
}