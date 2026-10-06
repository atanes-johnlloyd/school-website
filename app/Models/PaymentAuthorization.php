<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentAuthorization extends Model
{
    public const ACTION_PAY_ONLINE = 'pay_online';
    public const ACTION_PAY_CASH   = 'pay_cash';
    public const ACTION_DECLINE    = 'decline';

    protected $fillable = [
        'contribution_assignment_id', 'guardian_id', 'token',
        'action', 'action_at', 'decline_reason',
        'ip_address', 'user_agent', 'expires_at',
    ];

    protected $casts = [
        'action_at'  => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(ContributionAssignment::class, 'contribution_assignment_id');
    }

    public function guardian(): BelongsTo
    {
        return $this->belongsTo(StudentGuardian::class, 'guardian_id');
    }

    public function isPending(): bool
    {
        return is_null($this->action) && ! $this->isExpired();
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isUsed(): bool
    {
        return ! is_null($this->action);
    }

    public function choseOnline(): bool { return $this->action === self::ACTION_PAY_ONLINE; }
    public function choseCash(): bool   { return $this->action === self::ACTION_PAY_CASH; }
    public function choseDecline(): bool{ return $this->action === self::ACTION_DECLINE; }
}