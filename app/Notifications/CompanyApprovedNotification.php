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

        $paragraphs = [
            'Merhaba ' . e($notifiable->name) . '!',
            'Firmanız "' . e($this->company->name) . '" başarıyla onaylandı.',
            'Artık ihalelere teklif verebilir ve müşterilerle iletişime geçebilirsiniz.',
        ];
        if ($companyUrl) {
            $paragraphs[] = 'Firma sayfanız:';
        }
        $paragraphs[] = 'Teşekkür ederiz!';
        $buttons = [];
        if ($companyUrl) {
            $buttons[] = ['url' => $companyUrl, 'text' => 'Firma sayfasını görüntüle'];
        }
        $buttons[] = ['url' => $dashboardUrl, 'text' => 'Panele git'];
        $body = MailTemplateService::buildBodyHtml($paragraphs, $buttons);

        return (new MailMessage)->subject($subject)->view('emails.custom-body', ['body' => $body])->priority(1);
    }
}
