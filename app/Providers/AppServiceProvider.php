<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use App\Services\Notification\MailNotificationService;
use App\Services\Notification\NotificationService;
use App\Listeners\RecordUserLogin;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(NotificationService::class, MailNotificationService::class);

    }

    public function boot(): void
    {   
        Event::listen(Login::class, RecordUserLogin::class);
        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Password policy — applies everywhere Password::defaults() is used
        Password::defaults(function () {
            $rule = Password::min(12)->letters()->mixedCase()->numbers()->symbols();

            if ($this->app->isProduction()) {
                $rule = $rule->uncompromised();
            }

            return $rule;
        });
    }
}