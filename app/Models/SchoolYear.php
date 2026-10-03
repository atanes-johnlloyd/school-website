<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\Auditable;
class SchoolYear extends Model
{
    use Auditable;
    protected $fillable = ['label', 'start_date', 'end_date', 'is_active'];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean',
    ];

    public function terms()        { return $this->hasMany(Term::class); }
    public function sections()     { return $this->hasMany(Section::class); }
    public function enrollments()  { return $this->hasMany(Enrollment::class); }
    public function entranceExams()
    {
        return $this->hasMany(EntranceExam::class);
    }
    public function students(): BelongsToMany
    {
        // Replace 'enrollments' with your actual pivot table name if it's different
        return $this->belongsToMany(Student::class, 'enrollments'); 
    }
}