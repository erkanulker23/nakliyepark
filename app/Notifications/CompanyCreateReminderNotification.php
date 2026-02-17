<?php

namespace App\Notifications;

use App\Services\MailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/** Admin, nakliyeci rolündeki ancak firma oluşturmamış kullanıcıya hatırlatma maili göndermek için kullanır. */
class CompanyCreateReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** Hatırlatma maili için ek parametre gerekmez; kullanıcı bilgisi $notifiable üzerinden alınır. */
    public function __construct() {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = 'Nakliye firmanızı oluşturun - ' . config('seo.site_name', 'NakliyePark');
        $url = route('nakliyeci.company.create');

        $customBody = MailTemplateService::getCustomBody('company_create_reminder', [
            '{name}' => $notifiable->name,
            '{action_url}' => $url,
        ]);
        if ($customBody !== null) {
            return (new MailMessage)->subject($subject)->view('emails.custom-body', ['body' => $customBody])->priority(1);
        }

        $body = MailTemplateService::buildBodyHtml([
            'Merhaba ' . e($notifiable->name) . ',',
            'NakliyePark\'ta nakliyeci olarak kayıtlısınız ancak henüz firma bilgilerinizi oluşturmadınız.',
            'Firma bilgilerinizi tamamladığınızda admin onayından sonra ihalelere teklif verebileceksiniz.',
        ], [['url' => $url, 'text' => 'Firma bilgilerimi oluştur']]);

        return (new MailMessage)->subject($subject)->view('emails.custom-body', ['body' => $body])->priority(1);
    }
}
