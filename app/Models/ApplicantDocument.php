<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class ApplicantDocument extends Model
{
    use Auditable;
    protected $fillable = [
        'applicant_id', 'document_type',
        'file_path', 'file_name', 'file_size', 'mime_type',
        'status', 'remarks', 'verified_by', 'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function applicant()  { return $this->belongsTo(Applicant::class); }
    public function verifier()   { return $this->belongsTo(User::class, 'verified_by'); }

    public function getDownloadUrlAttribute(): ?string
    {
        return $this->file_path ? route('admin.applicant-documents.download', $this->id) : null;
    }
}