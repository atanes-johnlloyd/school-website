<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntranceExam extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_year_id', 'track_id', 'exam_name',
        'exam_date', 'exam_time', 'venue',
        'max_capacity', 'grade_level', 'status',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'exam_time' => 'string',   // HH:MM:SS
    ];

    // ─── Relationships ────────────────────────────────
    public function schoolYear() { return $this->belongsTo(SchoolYear::class); }
    public function track()      { return $this->belongsTo(Track::class); }
    public function results()    { return $this->hasMany(EntranceExamResult::class); }
    public function applicants() { return $this->belongsToMany(Applicant::class, 'entrance_exam_results'); }

    // ─── Computed ─────────────────────────────────────
    /**
     * Full datetime string of the exam start.
     */
    public function getStartsAtAttribute(): ?\Carbon\Carbon
    {
        if (! $this->exam_date || ! $this->exam_time) return null;
        return \Carbon\Carbon::parse($this->exam_date->toDateString() . ' ' . $this->exam_time);
    }

    /**
     * Computed status (Upcoming/Ongoing/Completed/Cancelled) — matches old logic.
     */
    public function getComputedStatusAttribute(): string
    {
        if ($this->status === 'Cancelled') return 'Cancelled';

        $start = $this->starts_at;
        if (! $start) return 'Upcoming';

        $now = now();
        if ($now->lt($start)) return 'Upcoming';

        // 4-hour session window
        $endTime = $start->copy()->addHours(4);
        if ($now->lt($endTime)) return 'Ongoing';

        return 'Completed';
    }

    // ─── Scopes ───────────────────────────────────────
    public function scopeUpcoming(Builder $q): Builder
    {
        return $q->where('status', '!=', 'Cancelled')
                 ->whereRaw("CONCAT(exam_date, ' ', exam_time) > ?", [now()]);
    }

    public function scopeForGrade(Builder $q, ?string $grade): Builder
    {
        if (! $grade || $grade === 'All') return $q;
        return $q->whereIn('grade_level', ['All', $grade]);
    }

    public function scopeForTrack(Builder $q, ?int $trackId): Builder
    {
        if (! $trackId) return $q;
        return $q->where(function ($sub) use ($trackId) {
            $sub->whereNull('track_id')->orWhere('track_id', $trackId);
        });
    }

    // ─── Helpers ──────────────────────────────────────
    public function enrolledCount(): int
    {
        return $this->results()->count();
    }

    public function remainingCapacity(): int
    {
        return max(0, $this->max_capacity - $this->enrolledCount());
    }
}