<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\Auditable;
use App\Notifications\CustomResetPassword;
use Illuminate\Support\Facades\Hash;

#[Fillable([
    'name',
    'email',
    'password',
    'must_change_password',
    'admin_position_id',
    'avatar_path',
    'status',
    'disabled_reason',
    'reset_token',
    'reset_expiry',
    'email_verified_at',
    'last_login_at',
    'two_factor_code',
    'two_factor_expires_at',
    'two_factor_attempts',
])]
#[Hidden(['password', 'remember_token'])]

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    use Auditable;

    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'password'             => 'hashed',
            'must_change_password' => 'boolean',
            'reset_expiry'         => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    public function adminPosition(): BelongsTo
    {
        return $this->belongsTo(AdminPosition::class);
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class, 'conversation_participants')
                    ->withPivot('last_read_at')
                    ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if (! $this->avatar_path) {
            return null;
        }

        $path = ltrim($this->avatar_path, '/');
        $path = preg_replace('#^storage/#', '', $path);

        return $path ? asset('storage/' . $path) : null;
    }

    protected $appends = ['avatar_url'];

    public function reviewedApplicants()
    {
        return $this->hasMany(Applicant::class, 'reviewed_by');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new CustomResetPassword($token));
    }

    /**
     * Whether this user must pass a second factor on login.
     * Admins and teachers always do; students never do.
     */
    public function requiresTwoFactor(): bool
    {
        //to be remove
        if (! config('auth.two_factor.enabled', true)) {
            return false;
        }

        return $this->hasAnyRole(['admin', 'teacher']);
    }
    /**
     * Generate a fresh 6-digit OTP, hash it, and store it with a 10-min expiry.
     * Resets the attempt counter. Returns the plain code so the caller can email it.
     */
    public function generateTwoFactorCode(): string
    {
        $plain = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $this->forceFill([
            'two_factor_code'       => Hash::make($plain),
            'two_factor_expires_at' => now()->addMinutes(10),
            'two_factor_attempts'   => 0,
        ])->save();

        return $plain;
    }

    /**
     * Verify a submitted code. Increments the attempt counter and clears
     * the code on success. Returns true only when the code is correct,
     * unexpired, and there are attempts remaining.
     */
    public function verifyTwoFactorCode(string $submitted): bool
    {
        // Already expired or already used → fail fast.
        if (blank($this->two_factor_code) || ! $this->two_factor_expires_at || $this->two_factor_expires_at->isPast()) {
            return false;
        }

        // Locked out after too many tries.
        if ($this->two_factor_attempts >= 5) {
            return false;
        }

        if (! Hash::check($submitted, $this->two_factor_code)) {
            $this->increment('two_factor_attempts');
            return false;
        }

        // Success — clear everything so the code can never be replayed.
        $this->forceFill([
            'two_factor_code'       => null,
            'two_factor_expires_at' => null,
            'two_factor_attempts'   => 0,
        ])->save();

        return true;
    }
}