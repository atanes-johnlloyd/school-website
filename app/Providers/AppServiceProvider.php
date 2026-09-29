<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use App\Services\Notification\MailNotificationService;
use App\Services\Notification\NotificationService;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(NotificationService::class, MailNotificationService::class);

    }

    public function boot(): void
    {
        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Password policy — applies everywhere Password::defaults() is used
        Password::defaults(function () {
            return Password::min(12)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised();  // checks against HaveIBeenPwned API
        });
    }
}