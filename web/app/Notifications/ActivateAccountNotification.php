<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class ActivateAccountNotification extends VerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Aktivasi Akun '.config('app.name', 'ExoInvite'))
            ->view('emails.auth.verify-account', [
                'verificationUrl' => $verificationUrl,
                'recipientName' => $notifiable->name,
                'appName' => config('app.name', 'ExoInvite'),
                'expiresInMinutes' => (int) config('auth.verification.expire', 60),
            ]);
    }
}
