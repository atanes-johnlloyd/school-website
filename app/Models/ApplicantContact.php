<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantContact extends Model
{
    protected $fillable = [
        'applicant_id', 'role', 'full_name', 'relationship',
        'occupation', 'contact_number', 'email',
    ];

    public function applicant() { return $this->belongsTo(Applicant::class); }
}