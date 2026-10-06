<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Contribution extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = [
        'created_by', 'school_year_id',
        'scope',                                     // ← NEW
        'class_id', 'section_id', 'strand_id', 'grade_level',   // ← section_id NEW
        'title', 'description', 'purpose',
        'amount_type', 'amount', 'min_amount', 'target_amount',
        'is_required', 'deadline_at',
        'is_published', 'published_at',
        'requires_guardian_consent',
    ];

    protected $casts = [
        'amount'          => 'decimal:2',
        'min_amount'      => 'decimal:2',
        'target_amount'   => 'decimal:2',
        'is_required'     => 'boolean',
        'is_published'    => 'boolean',
        'requires_guardian_consent' => 'boolean',
        'deadline_at'     => 'datetime',
        'published_at'    => 'datetime',
    ];

    // ─── Relationships ──────────────────────────────
    public function creator(): BelongsTo        { return $this->belongsTo(User::class, 'created_by'); }
    public function schoolYear(): BelongsTo     { return $this->belongsTo(SchoolYear::class); }
    public function classroom(): BelongsTo      { return $this->belongsTo(ClassRoom::class, 'class_id'); }
    public function strand(): BelongsTo         { return $this->belongsTo(Strand::class); }
    public function assignments(): HasMany      { return $this->hasMany(ContributionAssignment::class); }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    // ─── Scopes ─────────────────────────────────────
    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true)->whereNotNull('published_at');
    }

    public function scopeOpen(Builder $q): Builder
    {
        return $q->where(fn ($qq) => $qq
            ->whereNull('deadline_at')
            ->orWhere('deadline_at', '>', now())
        );
    }

    // ─── Helpers ────────────────────────────────────
    public function isFixedAmount(): bool
    {
        return $this->amount_type === 'fixed';
    }

    public function isExpired(): bool
    {
        return $this->deadline_at && $this->deadline_at->isPast();
    }

    public function isOpenAmount(): bool
    {
        return $this->amount_type === 'open';
    }

    /** Get the amount owed by a specific student (fixed) or default min (open). */
    public function amountForStudent(): ?float
    {
        return $this->isFixedAmount()
            ? (float) $this->amount
            : ($this->min_amount !== null ? (float) $this->min_amount : null);
    }

    /** Aggregate progress for the teacher/admin dashboard. */
    public function getProgressAttribute(): array
    {
        $all = $this->assignments;
        return [
            'total'    => $all->count(),
            'pending'  => $all->where('status', 'pending')->count(),
            'awaiting' => $all->where('status', 'awaiting_guardian')->count(),
            'authorized' => $all->where('status', 'authorized')->count(),
            'paid'     => $all->where('status', 'paid')->count(),
            'declined' => $all->where('status', 'declined')->count(),
            'collected'=> (float) $all->where('status', 'paid')->sum('amount_owed'),
            'target'   => (float) ($this->target_amount ?? $all->sum('amount_owed')),
        ];
    }

    /** Human label that works for both scopes. */
    public function getDisplayNameAttribute(): string
    {
        if ($this->scope === 'section') {
            return trim(($this->section?->name ?? 'Section') . ' • ' . ($this->section?->strand?->code ?? ''));
        }
        return ($this->classroom?->subject?->name ?? 'Class') . ' — ' . ($this->classroom?->section?->name ?? '');
    }
}