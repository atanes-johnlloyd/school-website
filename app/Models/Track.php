<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;
class Track extends Model
{
    use SoftDeletes;
    use Auditable;

    protected $fillable = ['code', 'name', 'description', 'is_active', 'icon', 'image_path', 'color'];
    protected $casts    = ['is_active' => 'boolean'];

    public function strands() { return $this->hasMany(Strand::class); }

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