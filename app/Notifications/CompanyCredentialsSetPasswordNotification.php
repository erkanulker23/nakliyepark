<?php

namespace App\Notifications;

use App\Models\Company;
use App\Services\MailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Admin firma oluşturduğunda veya düzenlediğinde nakliyeciye gönderilir.
 * E-postada giriş bilgileri ve "şifrenizi oluşturun" linki yer alır; nakliyeci linke tıklayıp kendi şifresini belirler.
 */
class CompanyCredentialsSetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Company $company,
        public string $setPasswordUrl
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $loginUrl = route('login');
        $companyName = $this->company->name;
        $email = $notifiable->getEmailForPasswordReset();

        $subject = 'NakliyePark - Firma giriş bilgileriniz';
        $lines = [
            "Merhaba {$notifiable->name},",
            "{$companyName} firması için hesabınız hazır.",
            'Giriş sayfası: ' . $loginUrl,
            'E-posta: ' . $email,
            'Henüz şifrenizi belirlemediyseniz, aşağıdaki linke tıklayarak kendi şifrenizi oluşturabilirsiniz. Bu link sınırlı süre geçerlidir.',
        ];
        $body = MailTemplateService::buildBodyHtml($lines, [
            ['url' => $this->setPasswordUrl, 'text' => 'Şifremi oluştur'],
        ]);

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.custom-body', ['body' => $body])
            ->priority(1);
    }
}
