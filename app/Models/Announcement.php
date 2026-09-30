<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Announcement extends Model
{
    use SoftDeletes, Auditable;

    public const PRIORITY_NORMAL    = 'normal';
    public const PRIORITY_IMPORTANT = 'important';
    public const PRIORITY_URGENT    = 'urgent';

    protected $fillable = [
        'created_by',
        'class_id',
        'title',
        'body',
        'is_pinned',
        'is_school_wide',
        'priority',
        'published_at',
        'expires_at',
        'image_path',
    ];

    protected $casts = [
        'is_pinned'      => 'boolean',
        'is_school_wide' => 'boolean',
        'published_at'   => 'datetime',
        'expires_at'     => 'datetime',
    ];

    // ─── Relationships ──────────────────────────────────
    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function classroom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    /** Alias for backward compat */
    public function klass()
    {
        return $this->classroom();
    }

    // ─── Scopes ─────────────────────────────────────────
    public function scopePublished(Builder $q): Builder
    {
        return $q->whereNotNull('published_at')
                 ->where('published_at', '<=', now());
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where(function ($query) {
            $query->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
        });
    }

    public function scopePinned(Builder $q): Builder
    {
        return $q->where('is_pinned', true);
    }

    public function scopeUrgent(Builder $q): Builder
    {
        return $q->where('priority', self::PRIORITY_URGENT);
    }

    public function scopeImportant(Builder $q): Builder
    {
        return $q->where('priority', self::PRIORITY_IMPORTANT);
    }

    public function scopeSchoolWide(Builder $q): Builder
    {
        return $q->where('is_school_wide', true);
    }

    /** Pinned first, then newest (legacy) */
    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderByDesc('is_pinned')->orderByDesc('published_at');
    }

    /**
     * Urgent → Important → Normal, then pinned first, then newest.
     */
    public function scopeOrderedByImportance(Builder $q): Builder
    {
        return $q
            ->orderByRaw("CASE priority
                WHEN 'urgent'    THEN 1
                WHEN 'important' THEN 2
                ELSE 3
            END")
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');
    }

    // ─── Computed ───────────────────────────────────────
    public function getIsDraftAttribute(): bool
    {
        return is_null($this->published_at);
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }

    protected $appends = ['image_url'];
}