<?php

namespace App\Notifications;

use App\Services\MailTemplateService;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

/**
 * E-posta doğrulama bildirimi. Kuyruğa alınmıyor (ShouldQueue yok) çünkü
 * kuyruk worker çalışmıyorsa kullanıcı e-postayı alamıyor; senkron gönderim
 * ile "tekrar gönder" tıklandığında mail anında gider.
 */
class VerifyEmailNotification extends Notification
{
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        $subject = \App\Models\Setting::get('mail_tpl_email_verification_subject', '');
        if ($subject === '') {
            $subject = 'E-posta adresinizi doğrulayın - ' . config('seo.site_name', 'NakliyePark');
        }

        $customBody = MailTemplateService::getCustomBody('email_verification', [
            '{verification_url}' => $verificationUrl,
            '{name}' => $notifiable->name,
        ]);
        if ($customBody !== null) {
            return (new MailMessage)->subject($subject)->view('emails.custom-body', ['body' => $customBody])->priority(1);
        }

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Merhaba ' . $notifiable->name . '!')
            ->line('Hesabınızı oluşturdunuz. E-posta adresinizi doğrulamak için aşağıdaki butona tıklayın.')
            ->action('E-postamı doğrula', $verificationUrl)
            ->line('Bu link 60 dakika geçerlidir. Eğer hesap oluşturmadıysanız bu e-postayı dikkate almayın.')
            ->priority(1);
    }

    protected function verificationUrl(object $notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
