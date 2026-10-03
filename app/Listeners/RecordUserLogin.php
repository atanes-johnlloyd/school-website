<?php

namespace App\Listeners;

use App\Support\AuditContext;
use Illuminate\Auth\Events\Login;

class RecordUserLogin
{
    public function handle(Login $event): void
    {
        $user = $event->user;
        if (! $user) {
            return;
        }

        try {
            AuditContext::wrap('login', function () use ($user) {
                // forceFill + save so mass-assignment guards don't block the columns.
                $user->forceFill([
                    'last_login_at' => now(),
                    'last_login_ip' => request()->ip(),
                ])->save();
            });
        } catch (\Throwable $e) {
            \Log::warning('Login audit failed', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }
}