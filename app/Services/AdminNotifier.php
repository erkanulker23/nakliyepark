<?php

namespace App\Services;

use App\Models\AdminNotification;

class AdminNotifier
{
    public static function notify(string $type, string $message, ?string $title = null, array $data = []): void
    {
        AdminNotification::notify($type, $message, $title, $data);
    }
}
