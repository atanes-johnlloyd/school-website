<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function ($model) {
            $model->writeAudit('created');
        });

        static::updated(function ($model) {
            $model->writeAudit('updated');
        });

        static::deleted(function ($model) {
            $model->writeAudit('deleted');
        });
    }

    protected function writeAudit(string $action): void
    {
        if (! auth()->check()) {
            return;
        }

        $old = null;
        $new = null;

        if ($action === 'updated') {
            $changes = $this->getChanges();
            unset($changes['updated_at']);
            if (empty($changes)) {
                return;
            }
            $old = array_intersect_key($this->getOriginal(), $changes);
            $new = $changes;
        } elseif ($action === 'created') {
            $new = $this->getAttributes();
        } elseif ($action === 'deleted') {
            $old = $this->getAttributes();
        }

        $sensitive = ['password', 'remember_token', 'reset_token', 'two_factor_secret'];
        foreach ($sensitive as $field) {
            if ($old && isset($old[$field])) $old[$field] = '[REDACTED]';
            if ($new && isset($new[$field])) $new[$field] = '[REDACTED]';
        }

        AuditLog::create([
            'user_id'        => auth()->id(),
            'action'         => $action,
            'auditable_type' => static::class,
            'auditable_id'   => $this->getKey(),
            'old_values'     => $old,
            'new_values'     => $new,
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
        ]);
    }
}