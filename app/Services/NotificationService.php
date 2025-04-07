<?php

namespace App\Services;

use Illuminate\Support\Facades\Notification;

class NotificationService
{
    public function sendNotification($data, $receiver, $notificationClass): void
    {
        if (!is_array($receiver)) {
            $receiver = [$receiver];
        }

        foreach ($receiver as $key => $value) {
            $notification = new $notificationClass($data);
            Notification::send($value, $notification);
        }
    }
}
