<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SafeNotificationService
{
    /**
     * Bildirim gönderir; hata durumunda kullanıcıya hata göstermez, log yazar, işlem geri alınmaz.
     * Queue sync ise aynı request'te çalışır; database/redis ise job kuyruğa alınır.
     */
    public static function sendToUser($notifiable, $notification, string $context = ''): void
    {
        try {
            if (is_object($notifiable) && method_exists($notifiable, 'notify')) {
                $notifiable->notify($notification);
            }
        } catch (\Throwable $e) {
            Log::channel('single')->error('SafeNotification: Kullanıcıya bildirim gönderilemedi.', [
                'context' => $context,
                'notifiable_type' => is_object($notifiable) ? get_class($notifiable) : null,
                'notifiable_id' => is_object($notifiable) && method_exists($notifiable, 'getKey') ? $notifiable->getKey() : null,
                'notification' => get_class($notification),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            try {
                \App\Models\AdminNotification::notify(
                    'mail_delivery_failed',
                    "Bildirim gönderilemedi: {$context}. " . $e->getMessage(),
                    'Mail/Bildirim hatası',
                    ['exception' => $e->getMessage()]
                );
            } catch (\Throwable $_) {
                // Admin bildirimi de başarısız olursa sessizce geç
            }
        }
    }

    /**
     * E-posta adresine (on-demand) bildirim gönderir; hata durumunda log yazar.
     */
    public static function sendToEmail(string $email, $notification, string $context = ''): void
    {
        try {
            Notification::route('mail', $email)->notify($notification);
        } catch (\Throwable $e) {
            Log::channel('single')->error('SafeNotification: E-posta adresine bildirim gönderilemedi.', [
                'context' => $context,
                'email' => $email,
                'notification' => get_class($notification),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            try {
                \App\Models\AdminNotification::notify(
                    'mail_delivery_failed',
                    "E-posta gönderilemedi ({$email}): {$context}. " . $e->getMessage(),
                    'Mail hatası',
                    ['exception' => $e->getMessage()]
                );
            } catch (\Throwable $_) {
            }
        }
    }
}
