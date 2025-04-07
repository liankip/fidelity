<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckFailedVoucherMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check-failed-voucher-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check failed voucher mail';
    

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $failedJobs = DB::table('failed_jobs')->get()->filter(function ($job) {
                $payload = json_decode($job->payload);
                // Check if the payload contains a specific model
                return isset($payload->displayName) && str_contains($payload->displayName, 'App\Mail\ApprovedVoucher');
            });

            if ($failedJobs->isEmpty()) {
                Log::info('No failed jobs found for the specified model.');
                return;
            }

            Log::warning(count($failedJobs) . ' failed jobs found. Retrying now...');
            
            // Retry and handle failed jobs
            foreach ($failedJobs as $job) {
                try {
                    // Explicitly convert UUID to string
                    $uuid = (string) $job->uuid;
                    
                    // Retry the specific job
                    Artisan::call('queue:retry', ['id' => $uuid]);
                    
                    Log::info("Retried job with UUID: {$uuid}");
                } catch (\Exception $e) {
                    Log::error("Failed to retry job with UUID {$uuid}: " . $e->getMessage());
                }
            }

            Log::info('Failed jobs retry process completed.');
        } catch (\Exception $e) {
            Log::error('Overall process error: ' . $e->getMessage());
        }
    }

}
