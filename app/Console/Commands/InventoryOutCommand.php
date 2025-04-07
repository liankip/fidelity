<?php

namespace App\Console\Commands;

use App\Mail\InventoryOutMail;
use App\Models\InventoryOut;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InventoryOutCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:inventory-out-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $checkDailyInventory = InventoryOut::whereDate('created_at', Carbon::today())->where('out', '!=',null)->where('out', '!=', 0)->get();

            if (count($checkDailyInventory) > 0) {
                $userEmail = [
                    'admin@satrianusa.group',
                    'ops@satrianusa.group',
                ];
                Mail::to($userEmail)->send(new InventoryOutMail());
            } else {
                return null;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
