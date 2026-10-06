<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    protected $fillable = [
        'payable_type', 'payable_id',
        'payer_user_id', 'student_id',
        'amount', 'currency', 'reference_no',
        'paymongo_checkout_id', 'paymongo_payment_id', 'paymongo_payment_intent_id',
        'checkout_url', 'status', 'payment_method',
        'paid_at', 'expires_at', 'raw_response',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'paid_at'      => 'datetime',
        'expires_at'   => 'datetime',
        'raw_response' => 'array',
    ];

    public function payable(): MorphTo  { return $this->morphTo(); }
    public function payer(): BelongsTo  { return $this->belongsTo(User::class, 'payer_user_id'); }
    public function student(): BelongsTo{ return $this->belongsTo(Student::class); }

    public function isPaid(): bool    { return $this->status === 'paid'; }
    public function isPending(): bool { return $this->status === 'pending'; }

    /**
     * Generate the next sequential reference like PAY-2026-000001.
     * Uses a lock to avoid race conditions.
     */
    public static function nextReference(): string
    {
        $year = now()->year;
        $prefix = "PAY-{$year}-";

        $last = static::where('reference_no', 'like', "{$prefix}%")
            ->lockForUpdate()
            ->orderByDesc('id')
            ->value('reference_no');

        $next = $last ? ((int) substr($last, -6)) + 1 : 1;

        return $prefix . str_pad((string) $next, 6, '0', STR_PAD_LEFT);
    }
}