<?php

namespace App\Console\Commands;

use App\Constants\EmailNotificationTypes;
use Illuminate\Console\Command;

class GenerateEmailNotificationType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email-notification-type:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate email notification type';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : void
    {
        $types = EmailNotificationTypes::getTypes();

        $notificationEmailTypeClass = app(\App\Models\NotificationEmailType::class);

        $notificationEmailTypeCreated = 0;

        foreach ($types as $type) {
            $notificationEmailTypeExists = $notificationEmailTypeClass::where('name', $type)->first();

            if (!$notificationEmailTypeExists) {
                $notificationEmailTypeClass::create(['name' => $type]);
                $this->info("Notification email type `{$type}` created");
                $notificationEmailTypeCreated++;
            }
        }

        if ($notificationEmailTypeCreated == 0) {
            $this->info("No notification email type created");
        }

    }
}
