<?php

namespace App\Notifications;

use App\Models\Ihale;
use App\Models\Teklif;
use App\Services\MailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeklifReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Ihale $ihale,
        public Teklif $teklif
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $companyName = $this->teklif->company?->name ?? 'Bir nakliye firması';
        $amount = number_format($this->teklif->amount, 0, ',', '.');
        $route = $this->ihale->user_id
            ? route('musteri.ihaleler.show', $this->ihale)
            : route('ihaleler.show', $this->ihale);
        $defaultSubject = 'Yeni teklif geldi - ' . $this->ihale->from_city . ' → ' . $this->ihale->to_city;
        $subject = \App\Models\Setting::get('mail_tpl_musteri_teklif_received_subject', '');
        if ($subject !== '') {
            $subject = str_replace(['{from_city}', '{to_city}'], [$this->ihale->from_city, $this->ihale->to_city], $subject);
        } else {
            $subject = $defaultSubject;
        }

        $customBody = MailTemplateService::getCustomBody('musteri_teklif_received', [
            '{from_city}' => $this->ihale->from_city,
            '{to_city}' => $this->ihale->to_city,
            '{firma_adi}' => $companyName,
            '{teklif_tutar}' => $amount . ' ₺',
            '{action_url}' => $route,
        ]);
        if ($customBody !== null) {
            return (new MailMessage)->subject($subject)->view('emails.custom-body', ['body' => $customBody]);
        }

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Merhaba!')
            ->line($companyName . ' ihalenize **' . $amount . ' ₺** teklif verdi.')
            ->action('Teklifi görüntüle', $route)
            ->line('Diğer firmalardan da teklif gelebilir; hepsini karşılaştırıp birini seçebilirsiniz.');
    }
}
