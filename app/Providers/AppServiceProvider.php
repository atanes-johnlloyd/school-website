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
use App\Support\PasswordPolicy;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(NotificationService::class, MailNotificationService::class);

    }

    public function boot(): void
    {
        Event::listen(Login::class, RecordUserLogin::class);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Password::defaults(fn () => PasswordPolicy::rule());
    }
}