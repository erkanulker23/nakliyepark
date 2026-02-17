<?php

namespace App\Notifications;

use App\Services\MailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuperAdminAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $message,
        public array $data = []
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = $this->data['url'] ?? null;
        $paragraphs = [
            e($this->title),
            e($this->message),
        ];
        $buttons = $url ? [['url' => $url, 'text' => 'İncele']] : null;
        $body = MailTemplateService::buildBodyHtml($paragraphs, $buttons);

        $subject = '[' . config('seo.site_name', 'NakliyePark') . '] ' . $this->title;

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.custom-body', ['body' => $body])
            ->priority(1);
    }
}
