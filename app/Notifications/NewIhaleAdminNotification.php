<?php

namespace App\Notifications;

use App\Models\Ihale;
use App\Services\MailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewIhaleAdminNotification extends Notification implements ShouldQueue
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
        $url = route('admin.ihaleler.show', $this->ihale);
        $guestOrMember = $this->ihale->user_id ? 'Üye' : 'Misafir';
        $defaultSubject = 'Yeni ihale talebi - ' . $this->ihale->from_city . ' → ' . $this->ihale->to_city;
        $subject = \App\Models\Setting::get('mail_tpl_admin_new_ihale_subject', '');
        if ($subject !== '') {
            $subject = str_replace(['{from_city}', '{to_city}'], [$this->ihale->from_city, $this->ihale->to_city], $subject);
        } else {
            $subject = $defaultSubject;
        }

        $customBody = MailTemplateService::getCustomBody('admin_new_ihale', [
            '{from_city}' => $this->ihale->from_city,
            '{to_city}' => $this->ihale->to_city,
            '{action_url}' => $url,
        ]);
        if ($customBody !== null) {
            return (new MailMessage)->subject($subject)->view('emails.custom-body', ['body' => $customBody]);
        }

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Yeni ihale talebi')
            ->line("Yeni bir nakliye talebi oluşturuldu ({$guestOrMember}).")
            ->line($this->ihale->from_city . ' → ' . $this->ihale->to_city)
            ->action('İhaleyi incele', $url);
    }
}
