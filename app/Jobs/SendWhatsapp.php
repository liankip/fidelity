<?php

namespace App\Jobs;

use App\Helpers\Whatsapp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsapp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $message;
    public $to;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message,$to)
    {
        $this->message = $message;
        $this->to = Whatsapp::convertPhoneNumber($to);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $session = config('app.wa_session');
        $endpoint = config('app.wa_api');

        $queryParams = "to=" . $this->to . "&text=" . $this->message . "&session=" . $session;

        try {
            Http::post($endpoint . "?" . $queryParams);
        }catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
