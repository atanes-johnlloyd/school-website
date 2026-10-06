<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentGuardian extends Model
{
    protected $fillable = [
        'student_id', 'full_name', 'relationship',
        'contact_number', 'email', 'is_primary',
        'source', 'source_contact_id',
    ];

    protected $casts = ['is_primary' => 'boolean'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function paymentAuthorizations(): HasMany
    {
        return $this->hasMany(PaymentAuthorization::class, 'guardian_id');
    }

    public function hasEmail(): bool
    {
        return filled($this->email);
    }
}