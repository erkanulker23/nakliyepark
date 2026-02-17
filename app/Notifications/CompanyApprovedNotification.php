<?php

namespace App\Notifications;

use App\Models\Company;
use App\Services\MailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompanyApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Company $company
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = \App\Models\Setting::get('mail_tpl_company_approved_subject', '');
        if ($subject === '') {
            $subject = 'Firmanız onaylandı - ' . config('seo.site_name', 'NakliyePark');
        }

        $dashboardUrl = route('nakliyeci.dashboard');
        $companyUrl = $this->company->slug ? route('firmalar.show', $this->company) : null;

        $customBody = MailTemplateService::getCustomBody('company_approved', [
            '{name}' => $notifiable->name,
            '{company_name}' => $this->company->name,
            '{dashboard_url}' => $dashboardUrl,
            '{company_url}' => $companyUrl ?? '',
        ]);
        if ($customBody !== null) {
            return (new MailMessage)->subject($subject)->view('emails.custom-body', ['body' => $customBody])->priority(1);
        }

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting('Merhaba ' . $notifiable->name . '!')
            ->line('Firmanız "' . $this->company->name . '" başarıyla onaylandı.')
            ->line('Artık ihalelere teklif verebilir ve müşterilerle iletişime geçebilirsiniz.');

        if ($companyUrl) {
            $mail->line('Firma sayfanız:')
                ->action('Firma sayfasını görüntüle', $companyUrl);
        }

        $mail->action('Panele git', $dashboardUrl)
            ->line('Teşekkür ederiz!');

        return $mail->priority(1);
    }
}
