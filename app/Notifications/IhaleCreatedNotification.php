<?php

namespace App\Notifications;

use App\Models\Ihale;
use App\Services\MailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IhaleCreatedNotification extends Notification implements ShouldQueue
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
        $route = $this->ihale->user_id
            ? route('musteri.ihaleler.show', $this->ihale)
            : '';
        $defaultSubject = 'Nakliye talebiniz alındı - ' . $this->ihale->from_city . ' → ' . $this->ihale->to_city;
        $subject = \App\Models\Setting::get('mail_tpl_musteri_ihale_created_subject', '');
        if ($subject !== '') {
            $subject = str_replace(['{from_city}', '{to_city}'], [$this->ihale->from_city, $this->ihale->to_city], $subject);
        } else {
            $subject = $defaultSubject;
        }

        $customBody = MailTemplateService::getCustomBody('musteri_ihale_created', [
            '{from_city}' => $this->ihale->from_city,
            '{to_city}' => $this->ihale->to_city,
            '{action_url}' => $route,
        ]);
        if ($customBody !== null) {
            return (new MailMessage)->subject($subject)->view('emails.custom-body', ['body' => $customBody]);
        }

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting('Merhaba!')
            ->line('Nakliye talebiniz başarıyla alındı.')
            ->line('İnceleme sonrası talebiniz yayına alınacak ve nakliye firmaları size teklif gönderebilecek.');

        if ($route) {
            $mail->action('Talebinizi görüntüle', $route);
        }

        return $mail;
    }
}
