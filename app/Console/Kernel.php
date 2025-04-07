<?php

namespace App\Console;

use App\Jobs\SendToPNotification;
use App\Jobs\MinutesOfMeetingApproval;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(SendToPNotification::class)->dailyAt('10:00');
        $schedule->command('command:inventory-command')->dailyAt('16:59')->timezone('Asia/Jakarta');
        // $schedule->command('command:inventory-out-command')->dailyAt('16:59')->timezone('Asia/Jakarta');
        $schedule->command('command:boq-expired-command')->dailyAt('08:00')->timezone('Asia/Jakarta');
        $schedule->command('command:task-start-command')->dailyAt('09:00')->timezone('Asia/Jakarta');
        $schedule->command('command:task-finish-command')->dailyAt('09:01')->timezone('Asia/Jakarta');
        $schedule->command('command:check-failed-voucher-mail')->dailyAt('17:00')->timezone('Asia/Jakarta');
        // $schedule->job(SendToPNotification::class)->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
