<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;

class CustomResetPassword extends BaseResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $expiresIn = config(
            'auth.passwords.' . config('auth.defaults.passwords') . '.expire',
            60
        );

        return (new MailMessage)
            ->subject('Reset Your Password - Salawag SHS')
            ->view('emails.password-reset', [
                'full_name'    => $notifiable->name,
                'email'        => $notifiable->getEmailForPasswordReset(),
                'reset_link'   => $resetUrl,
                'requested_at' => Carbon::now()->format('F d, Y \a\t g:i A'),
                'expires_in'   => $expiresIn,
                'ip_address'   => request()->ip(),
            ]);
    }
}