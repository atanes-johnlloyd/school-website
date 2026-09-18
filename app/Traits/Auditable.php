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
        // Skip if no authenticated user (e.g., seeders, queue jobs)
        if (! auth()->check()) {
            return;
        }

        AuditLog::create([
            'user_id'        => auth()->id(),
            'action'         => $action,
            'auditable_type' => static::class,
            'auditable_id'   => $this->getKey(),
            'old_values'     => $action === 'updated' ? $this->getOriginal() : null,
            'new_values'     => $action === 'deleted' ? null : $this->getAttributes(),
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
        ]);
    }
}