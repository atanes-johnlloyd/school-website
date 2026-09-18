<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    protected $fillable = ['label', 'start_date', 'end_date', 'is_active'];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean',
    ];

    public function terms()        { return $this->hasMany(Term::class); }
    public function sections()     { return $this->hasMany(Section::class); }
    public function enrollments()  { return $this->hasMany(Enrollment::class); }
}