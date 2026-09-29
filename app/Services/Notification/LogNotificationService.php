<?php

namespace App\Services\Notification;

use Illuminate\Support\Facades\Log;

class LogNotificationService implements NotificationService
{
    public function send(string $to, string $subject, string $templateKey, array $placeholders = []): bool
    {
        Log::info('[NOTIFICATION - LOG]', [
            'to'           => $to,
            'subject'      => $subject,
            'template'     => $templateKey,
            'placeholders' => $placeholders,
        ]);

        @file_put_contents(
            storage_path('logs/notifications.log'),
            sprintf(
                "[%s] %s → %s (template: %s) %s\n",
                now()->toDateTimeString(),
                $subject,
                $to,
                $templateKey,
                json_encode($placeholders)
            ),
            FILE_APPEND
        );

        return true;
    }

    public function sendMany(array $recipients, string $subject, string $templateKey, array $placeholders = []): int
    {
        $count = 0;
        foreach ($recipients as $to) {
            if ($this->send($to, $subject, $templateKey, $placeholders)) {
                $count++;
            }
        }
        return $count;
    }
}