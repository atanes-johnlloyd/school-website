<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Track extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'name', 'description', 'is_active', 'icon', 'image_path', 'color'];
    protected $casts    = ['is_active' => 'boolean'];

    public function strands() { return $this->hasMany(Strand::class); }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }

    protected $appends = ['image_url'];
}