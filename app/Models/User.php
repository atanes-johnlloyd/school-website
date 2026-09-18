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

#[Fillable([
    'name',
    'email',
    'password',
    'must_change_password',
    'admin_position_id',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'password'             => 'hashed',
            'must_change_password' => 'boolean',
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
}