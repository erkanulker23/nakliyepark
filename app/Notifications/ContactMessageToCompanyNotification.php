<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use App\Services\MailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactMessageToCompanyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ContactMessage $contactMessage
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $ihale = $this->contactMessage->ihale;
        $fromName = $this->contactMessage->fromUser->name ?? 'Müşteri';
        $route = route('nakliyeci.teklifler.index');
        $defaultSubject = 'Müşteri mesajı - ' . $ihale->from_city . ' → ' . $ihale->to_city;
        $subject = \App\Models\Setting::get('mail_tpl_nakliyeci_contact_message_subject', '');
        if ($subject !== '') {
            $subject = str_replace(['{from_city}', '{to_city}'], [$ihale->from_city, $ihale->to_city], $subject);
        } else {
            $subject = $defaultSubject;
        }

        $customBody = MailTemplateService::getCustomBody('nakliyeci_contact_message', [
            '{from_city}' => $ihale->from_city,
            '{to_city}' => $ihale->to_city,
            '{musteri_adi}' => $fromName,
            '{action_url}' => $route,
        ]);
        if ($customBody !== null) {
            return (new MailMessage)->subject($subject)->view('emails.custom-body', ['body' => $customBody])->priority(1);
        }

        $body = MailTemplateService::buildBodyHtml([
            'Merhaba!',
            e($fromName) . ' sizinle iletişime geçmek istiyor (kabul ettiğiniz teklif üzerinden).',
            '<strong>Mesaj:</strong><br>' . nl2br(e($this->contactMessage->message)),
            'Müşteri ile iletişim bilgileriniz üzerinden iletişime geçebilirsiniz.',
        ], [['url' => $route, 'text' => 'Tekliflerim']]);

        return (new MailMessage)->subject($subject)->view('emails.custom-body', ['body' => $body])->priority(1);
    }
}
