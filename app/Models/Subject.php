<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;
class Subject extends Model
{
    use SoftDeletes;
    use Auditable;

    protected $fillable = [
        'code', 'name', 'description', 'strand_id',
        'grade_level', 'is_core', 'hours',
        'prerequisite_subject_id', 'is_active',
        'image_path', 'icon', 'color',
    ];

    protected $casts = [
        'is_core'   => 'boolean',
        'is_active' => 'boolean',
    ];

    public function strand()       { return $this->belongsTo(Strand::class); }
    public function prerequisite() { return $this->belongsTo(Subject::class, 'prerequisite_subject_id'); }
    public function classroom()      { return $this->hasMany(ClassRoom::class, 'subject_id'); }
    public function questions()    { return $this->hasMany(Question::class); }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }

    protected $appends = ['image_url'];
}