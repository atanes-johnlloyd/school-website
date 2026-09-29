<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Applicant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference_number', 'school_year_id', 'strand_id', 'applicant_type',
        'first_name', 'middle_name', 'last_name', 'extension_name',
        'lrn', 'date_of_birth', 'sex', 'religion', 'contact_number', 'email',
        'house_street', 'barangay', 'municipality', 'province', 'zip_code',
        'prev_school_name', 'prev_school_address', 'prev_school_type', 'last_school_year',
        'desired_grade_level',
        'status', 'rejection_reason', 'reviewed_by', 'reviewed_at',
        'converted_student_id', 'submitted_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'reviewed_at'   => 'datetime',
        'submitted_at'  => 'datetime',
    ];

    // ─── Relationships ─────────────────────────────
    public function schoolYear()   { return $this->belongsTo(SchoolYear::class); }
    public function strand()       { return $this->belongsTo(Strand::class); }
    public function reviewer()     { return $this->belongsTo(User::class, 'reviewed_by'); }
    public function convertedStudent() { return $this->belongsTo(Student::class, 'converted_student_id'); }
    public function documents()    { return $this->hasMany(ApplicantDocument::class); }
    public function contacts()     { return $this->hasMany(ApplicantContact::class); }

    // ─── Scopes ────────────────────────────────────
    public function scopePending(Builder $q): Builder       { return $q->where('status', 'pending'); }
    public function scopeUnderReview(Builder $q): Builder   { return $q->where('status', 'under_review'); }
    public function scopeApproved(Builder $q): Builder      { return $q->where('status', 'approved'); }
    public function scopeEnrolled(Builder $q): Builder      { return $q->where('status', 'enrolled'); }

    // ─── Helpers ───────────────────────────────────
    public function getFullNameAttribute(): string
    {
        return trim(collect([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->extension_name,
        ])->filter()->implode(' '));
    }

    /**
     * Generate the next reference number for a given year.
     * Format: YYYY-NNNN  (e.g., 2026-0001)
     */
    public static function generateReferenceNumber(int $year = null): string
    {
        $year = $year ?: (int) now()->format('Y');

        $latest = static::withTrashed()
            ->where('reference_number', 'like', "{$year}-%")
            ->orderByDesc('reference_number')
            ->value('reference_number');

        $next = $latest
            ? ((int) substr($latest, -4)) + 1
            : 1;

        return sprintf('%d-%04d', $year, $next);
    }

    public function entranceExamResults()
    {
        return $this->hasMany(EntranceExamResult::class);
    }

    public function currentExamResult()
    {
        return $this->hasOne(EntranceExamResult::class)->latestOfMany();
    }
}