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

        $line2 = $this->role === 'nakliyeci'
            ? 'Firma bilgilerinizi tamamlayıp onay aldıktan sonra ihalelere teklif verebilirsiniz.'
            : 'Nakliye talebi oluşturarak firmalardan teklif alabilirsiniz.';
        $body = MailTemplateService::buildBodyHtml([
            'Merhaba ' . e($notifiable->name) . '!',
            'NakliyePark ailesine hoş geldiniz.',
            $line2,
        ], [['url' => $dashboardUrl, 'text' => 'Panele git']]);

        return (new MailMessage)->subject($subject)->view('emails.custom-body', ['body' => $body])->priority(1);
    }
}
