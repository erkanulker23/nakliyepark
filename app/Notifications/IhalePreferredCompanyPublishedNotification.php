<?php

namespace App\Notifications;

use App\Models\Ihale;
use App\Services\MailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IhalePreferredCompanyPublishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Ihale $ihale
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $route = route('nakliyeci.ihaleler.show', $this->ihale);
        $defaultSubject = 'Sizi tercih eden bir ihale yayında - ' . $this->ihale->from_city . ' → ' . $this->ihale->to_city;
        $subject = \App\Models\Setting::get('mail_tpl_nakliyeci_ihale_preferred_subject', '');
        if ($subject !== '') {
            $subject = str_replace(['{from_city}', '{to_city}'], [$this->ihale->from_city, $this->ihale->to_city], $subject);
        } else {
            $subject = $defaultSubject;
        }

        $customBody = MailTemplateService::getCustomBody('nakliyeci_ihale_preferred', [
            '{from_city}' => $this->ihale->from_city,
            '{to_city}' => $this->ihale->to_city,
            '{action_url}' => $route,
        ]);
        if ($customBody !== null) {
            return (new MailMessage)->subject($subject)->view('emails.custom-body', ['body' => $customBody]);
        }

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Merhaba!')
            ->line('Bir müşteri sizi tercih ederek taşınma talebi oluşturdu. İhale onaylandı ve yayına alındı.')
            ->line('Hemen teklif vererek müşteriye ulaşabilirsiniz.')
            ->action('İhaleye git ve teklif ver', $route);
    }
}
