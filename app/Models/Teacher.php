<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'user_id', 'employee_no', 'sex', 'date_of_birth', 'contact_number',
        'date_hired', 'department', 'specialization', 'is_active',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_hired'    => 'date',
        'is_active'     => 'boolean',
    ];

    public function user()      { return $this->belongsTo(User::class); }
    public function classroom()   { return $this->hasMany(ClassRoom::class, 'teacher_id'); }
    public function sections()  { return $this->hasMany(Section::class, 'adviser_id'); }
    public function questions() { return $this->hasMany(Question::class); }
}