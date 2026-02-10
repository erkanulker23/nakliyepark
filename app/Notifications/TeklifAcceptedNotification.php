<?php

namespace App\Notifications;

use App\Models\Ihale;
use App\Models\Teklif;
use App\Services\MailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeklifAcceptedNotification extends Notification implements ShouldQueue
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
        $amount = number_format($this->teklif->amount, 0, ',', '.');
        $route = route('nakliyeci.teklifler.index');
        $defaultSubject = 'Teklifiniz kabul edildi - ' . $this->ihale->from_city . ' → ' . $this->ihale->to_city;
        $subject = \App\Models\Setting::get('mail_tpl_nakliyeci_teklif_accepted_subject', '');
        if ($subject !== '') {
            $subject = str_replace(['{from_city}', '{to_city}'], [$this->ihale->from_city, $this->ihale->to_city], $subject);
        } else {
            $subject = $defaultSubject;
        }

        $customBody = MailTemplateService::getCustomBody('nakliyeci_teklif_accepted', [
            '{from_city}' => $this->ihale->from_city,
            '{to_city}' => $this->ihale->to_city,
            '{teklif_tutar}' => $amount . ' ₺',
            '{action_url}' => $route,
        ]);
        if ($customBody !== null) {
            return (new MailMessage)->subject($subject)->view('emails.custom-body', ['body' => $customBody]);
        }

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Tebrikler!')
            ->line($this->ihale->from_city . ' → ' . $this->ihale->to_city . ' ihalesinde **' . $amount . ' ₺** tutarındaki teklifiniz müşteri tarafından kabul edildi.')
            ->line('Müşteri sizinle iletişime geçebilir. Taşıma detaylarını birlikte netleştirebilirsiniz.')
            ->action('Tekliflerim', $route);
    }
}
