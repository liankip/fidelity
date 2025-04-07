<?php

namespace App\Jobs;

use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Remainderapproval implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = PurchaseOrder::where("status","Wait For Approval")->get();
        PurchaseOrder::where("status","Wait For Approval")->update([
            "status" => "wkwkw"
        ]);
        // $datanow = date("d F Y, H:i", strtotime($data);
        // foreach ($data as $key => $value) {
        //     if ($value) {
        //         # code...
        //     }
        // }

    }
}
