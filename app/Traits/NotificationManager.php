<?php

namespace App\Traits;

use App\Roles\Role;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

trait NotificationManager
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

    public function sendNotificationToManager($data, $notificationClass): void
    {
        $receiver = User::role([Role::MANAGER, Role::IT])->get();

        foreach ($receiver as $key => $value) {
            $notification = new $notificationClass($data);
            Notification::send($value, $notification);
        }
    }
}
