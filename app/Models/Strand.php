<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;
class Strand extends Model
{
    use SoftDeletes;
    use Auditable;

    protected $fillable = ['track_id', 'code', 'name', 'description', 'is_active', 'image_path', 'icon', 'color',];
    protected $casts    = ['is_active' => 'boolean'];

    public function track()      { return $this->belongsTo(Track::class); }
    public function subjects()   { return $this->hasMany(Subject::class); }
    public function sections()   { return $this->hasMany(Section::class); }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        $path = ltrim($this->image_path, '/');
        $path = preg_replace('#^storage/#', '', $path);

        return $path ? asset('storage/' . $path) : null;
    }

    protected $appends = ['image_url'];
}