<?php

namespace App\Services;

use App\Models\AdminNotification;
use App\Notifications\SuperAdminAlertNotification;

class AdminNotifier
{
    public static function notify(string $type, string $message, ?string $title = null, array $data = []): void
    {
        AdminNotification::notify($type, $message, $title, $data);

        $superAdminEmail = config('app.super_admin_email');
        if ($superAdminEmail && is_string($superAdminEmail) && $superAdminEmail !== '') {
            SafeNotificationService::sendToEmail(
                $superAdminEmail,
                new SuperAdminAlertNotification($title ?? $type, $message, $data),
                'super_admin_alert_' . $type
            );
        }
    }
}
