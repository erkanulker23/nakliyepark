<?php

namespace App\Notifications;

use App\Services\MailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $role
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $templateKey = $this->role === 'nakliyeci' ? 'nakliyeci_welcome' : 'musteri_welcome';
        $subject = \App\Models\Setting::get('mail_tpl_' . $templateKey . '_subject', '');
        if ($subject === '') {
            $subject = 'Hoş geldiniz! - ' . config('seo.site_name', 'NakliyePark');
        }

        $dashboardUrl = $this->role === 'nakliyeci'
            ? route('nakliyeci.dashboard')
            : route('musteri.dashboard');

        $customBody = MailTemplateService::getCustomBody($templateKey, [
            '{name}' => $notifiable->name,
            '{action_url}' => $dashboardUrl,
        ]);
        if ($customBody !== null) {
            return (new MailMessage)->subject($subject)->view('emails.custom-body', ['body' => $customBody])->priority(1);
        }

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting('Merhaba ' . $notifiable->name . '!')
            ->line('NakliyePark ailesine hoş geldiniz.');

        if ($this->role === 'nakliyeci') {
            $mail->line('Firma bilgilerinizi tamamlayıp onay aldıktan sonra ihalelere teklif verebilirsiniz.')
                ->action('Panele git', $dashboardUrl);
        } else {
            $mail->line('Nakliye talebi oluşturarak firmalardan teklif alabilirsiniz.')
                ->action('Panele git', $dashboardUrl);
        }

        return $mail->priority(1);
    }
}
