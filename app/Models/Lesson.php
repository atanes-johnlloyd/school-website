<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
class Lesson extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'class_id',
        'class_module_id',
        'title',
        'body',
        'position',
        'is_published',
        'thumbnail_path',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'position'     => 'integer',
    ];

    public function classroom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function module()
    {
        return $this->belongsTo(ClassModule::class, 'class_module_id');
    }

    public function attachments()
    {
        return $this->hasMany(LessonAttachment::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_path ? asset('storage/' . $this->thumbnail_path) : null;
    }

    protected $appends = ['thumbnail_url'];
}