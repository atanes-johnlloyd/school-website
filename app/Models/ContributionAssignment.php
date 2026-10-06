<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ContributionAssignment extends Model
{
    public const STATUS_PENDING      = 'pending';
    public const STATUS_NOTIFIED     = 'notified';
    public const STATUS_PAID         = 'paid';
    public const STATUS_DECLINED     = 'declined';
    public const STATUS_CASH_PENDING = 'cash_pending';
    public const STATUS_OVERDUE      = 'overdue';
    public const STATUS_WAIVED       = 'waived';

    protected $fillable = [
        'contribution_id', 'student_id', 'amount_owed',
        'status', 'guardian_id',
        'authorized_at', 'declined_at', 'decline_reason', 'paid_at',
        'consent_resend_count', 'last_consent_sent_at',
    ];

    protected $casts = [
        'amount_owed'          => 'decimal:2',
        'authorized_at'        => 'datetime',
        'declined_at'          => 'datetime',
        'paid_at'              => 'datetime',
        'last_consent_sent_at' => 'datetime',
    ];

    public function contribution(): BelongsTo  { return $this->belongsTo(Contribution::class); }
    public function student(): BelongsTo       { return $this->belongsTo(Student::class); }
    public function guardian(): BelongsTo      { return $this->belongsTo(StudentGuardian::class, 'guardian_id'); }

    public function authorizations(): HasMany
    {
        return $this->hasMany(PaymentAuthorization::class, 'contribution_assignment_id');
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function isPaid(): bool         { return $this->status === self::STATUS_PAID; }
    public function isNotified(): bool     { return $this->status === self::STATUS_NOTIFIED; }
    public function isCashPending(): bool  { return $this->status === self::STATUS_CASH_PENDING; }
    public function isDeclined(): bool     { return $this->status === self::STATUS_DECLINED; }
    public function isOverdue(): bool      { return $this->status === self::STATUS_OVERDUE; }

    /** Can the teacher/student trigger a resend of the payment email? */
    public function canNotify(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_NOTIFIED,
            self::STATUS_DECLINED,
            self::STATUS_OVERDUE,
        ], true);
    }
}