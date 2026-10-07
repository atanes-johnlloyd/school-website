<?php

namespace App\Traits;

use App\Models\AuditLog;
use App\Support\AuditContext;

trait Auditable
{
    /** Max characters stored for any single string value in old/new payloads. */
    protected static int $auditStringCap = 1000;

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
        // ✅ FIX: Resolve an actor even when no HTTP user is authenticated.
        // Priority: explicit AuditContext actor > auth() user > null (system).
        $actorId = auth()->id() ?? AuditContext::actorId() ?? null;

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

        // Redact sensitive fields
        $sensitive = ['password', 'remember_token', 'reset_token', 'two_factor_secret'];
        foreach ($sensitive as $field) {
            if ($old && isset($old[$field])) $old[$field] = '[REDACTED]';
            if ($new && isset($new[$field])) $new[$field] = '[REDACTED]';
        }

        $old = $this->truncateAuditStrings($old);
        $new = $this->truncateAuditStrings($new);

        // Attach business context if set
        $event = AuditContext::event();
        $extra = AuditContext::extra();

        if ($event) {
            $new = is_array($new) ? $new : [];
            $new['event'] = $event;
        }
        if (! empty($extra)) {
            $new = array_merge(is_array($new) ? $new : [], $extra);
        }

        // ✅ FIX: `user_id` is now nullable. If no actor is resolvable, we still
        // record the audit row with `user_id = null` — the action is preserved
        // rather than silently dropped.
        AuditLog::create([
            'user_id'        => $actorId,
            'action'         => $action,
            'auditable_type' => static::class,
            'auditable_id'   => $this->getKey(),
            'old_values'     => $old,
            'new_values'     => $new,
            'ip_address'     => app()->runningInConsole() ? null : request()->ip(),
            'user_agent'     => app()->runningInConsole() ? 'console' : request()->userAgent(),
        ]);
    }

    protected function truncateAuditStrings(?array $data): ?array
    {
        if (! is_array($data)) {
            return $data;
        }

        $cap = static::$auditStringCap;

        foreach ($data as $key => $value) {
            if (is_string($value) && mb_strlen($value) > $cap) {
                $data[$key] = mb_substr($value, 0, $cap) . '…[truncated]';
            } elseif (is_array($value)) {
                $data[$key] = $this->truncateAuditStrings($value);
            }
        }

        return $data;
    }
}