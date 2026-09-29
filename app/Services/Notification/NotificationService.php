<?php

namespace App\Services\Notification;

interface NotificationService
{
    /**
     * Send one email using a named template.
     */
    public function send(string $to, string $subject, string $templateKey, array $placeholders = []): bool;

    /**
     * Send the same email to multiple recipients.
     */
    public function sendMany(array $recipients, string $subject, string $templateKey, array $placeholders = []): int;
}