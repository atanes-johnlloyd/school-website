<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Strand extends Model
{
    use SoftDeletes;

    protected $fillable = ['track_id', 'code', 'name', 'description', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    public function track()      { return $this->belongsTo(Track::class); }
    public function subjects()   { return $this->hasMany(Subject::class); }
    public function sections()   { return $this->hasMany(Section::class); }
}