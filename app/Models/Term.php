<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
class Term extends Model
{
    use Auditable;
    protected $fillable = ['school_year_id', 'name', 'start_date', 'end_date', 'is_active'];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean',
    ];

    public function schoolYear() { return $this->belongsTo(SchoolYear::class); }
    public function classroom()    { return $this->hasMany(ClassRoom::class, 'term_id'); }
}