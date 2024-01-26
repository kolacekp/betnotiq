<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetPassword extends ResetPasswordNotification
{
    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage)
            ->subject(Lang::get('auth.reset_password_notification'))
            ->greeting(Lang::get('auth.email_greeting'))
            ->line(Lang::get('auth.reset_password_email_text'))
            ->action(Lang::get('auth.reset_password_email_button'), $url)
            ->line(Lang::get('auth.reset_password_email_expiration', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(Lang::get('auth.reset_password_email_note'))
            ->salutation(Lang::get('auth.email_salutation'));
    }
}
