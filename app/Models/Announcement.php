<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Announcement extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = [
        'created_by',
        'class_id',
        'title',
        'body',
        'is_pinned',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'is_pinned'    => 'boolean',
        'published_at' => 'datetime',
        'expires_at'   => 'datetime',
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

    // Pinned first, then newest
    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderByDesc('is_pinned')->orderByDesc('published_at');
    }
}