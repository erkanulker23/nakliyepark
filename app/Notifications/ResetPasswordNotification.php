<?php

namespace App\Notifications;

use App\Services\MailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $token
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url()->route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        $subject = \App\Models\Setting::get('mail_tpl_password_reset_subject', '');
        if ($subject === '') {
            $subject = 'Şifre sıfırlama - NakliyePark';
        }

        $expire = (int) config('auth.passwords.users.expire', 60);

        $customBody = MailTemplateService::getCustomBody('password_reset', [
            '{reset_url}' => $url,
            '{expire_minutes}' => (string) $expire,
        ]);
        if ($customBody !== null) {
            return (new MailMessage)->subject($subject)->view('emails.custom-body', ['body' => $customBody])->priority(1);
        }

        $body = MailTemplateService::buildBodyHtml([
            'Merhaba!',
            'Hesabınız için şifre sıfırlama talebinde bulundunuz.',
            'Bu link ' . $expire . ' dakika geçerlidir.',
            'Eğer bu talebi siz yapmadıysanız, bu e-postayı dikkate almayın.',
        ], [['url' => $url, 'text' => 'Şifremi sıfırla']]);

        return (new MailMessage)->subject($subject)->view('emails.custom-body', ['body' => $body])->priority(1);
    }
}
