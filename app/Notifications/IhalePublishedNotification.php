<?php

namespace App\Notifications;

use App\Models\Ihale;
use App\Services\MailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IhalePublishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Ihale $ihale,
        public bool $forGuest = false
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $route = $this->forGuest
            ? route('ihaleler.show', $this->ihale)
            : route('musteri.ihaleler.show', $this->ihale);

        $defaultSubject = 'İhaleniz yayında - ' . $this->ihale->from_city . ' → ' . $this->ihale->to_city;
        $subject = \App\Models\Setting::get('mail_tpl_musteri_ihale_published_subject', '');
        if ($subject !== '') {
            $subject = str_replace(['{from_city}', '{to_city}'], [$this->ihale->from_city, $this->ihale->to_city], $subject);
        } else {
            $subject = $defaultSubject;
        }

        $customBody = MailTemplateService::getCustomBody('musteri_ihale_published', [
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
            ->line('İhale talebiniz onaylandı ve yayına alındı.')
            ->line('Nakliye firmaları artık size teklif gönderebilir. Gelen teklifleri panelinizden takip edebilirsiniz.')
            ->action('İhalemi görüntüle', $route);
    }
}
