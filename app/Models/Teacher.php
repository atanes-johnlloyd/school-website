<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;
class Teacher extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Auditable;

    protected $fillable = [
        'user_id', 'employee_no', 'sex', 'date_of_birth', 'contact_number',
        'date_hired', 'department', 'specialization', 'is_active', 'is_publicly_visible',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_hired'    => 'date',
        'is_active'     => 'boolean',
        'is_publicly_visible'=> 'boolean',
    ];

    public function user()      { return $this->belongsTo(User::class); }
    public function classroom()   { return $this->hasMany(ClassRoom::class, 'teacher_id'); }
    public function sections()  { return $this->hasMany(Section::class, 'adviser_id'); }
    public function questions() { return $this->hasMany(Question::class); }
}