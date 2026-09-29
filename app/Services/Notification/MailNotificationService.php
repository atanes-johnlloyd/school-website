<?php

namespace App\Services\Notification;

use App\Mail\TemplateMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailNotificationService implements NotificationService
{
    public function send(string $to, string $subject, string $templateKey, array $placeholders = []): bool
    {
        try {
            Mail::to($to)->send(new TemplateMail($templateKey, $placeholders, $subject));

            Log::info('[MAIL] Sent', [
                'to'       => $to,
                'subject'  => $subject,
                'template' => $templateKey,
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('[MAIL] Failed: ' . $e->getMessage(), [
                'to'       => $to,
                'subject'  => $subject,
                'template' => $templateKey,
            ]);

            return false;
        }
    }

    public function sendMany(array $recipients, string $subject, string $templateKey, array $placeholders = []): int
    {
        $sent = 0;
        foreach ($recipients as $to) {
            if ($this->send($to, $subject, $templateKey, $placeholders)) {
                $sent++;
            }
        }
        return $sent;
    }
}