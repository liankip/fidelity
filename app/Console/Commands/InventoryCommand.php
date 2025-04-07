<?php

namespace App\Console\Commands;

use App\Mail\InventoryMail;
use App\Models\Inventory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InventoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:inventory-command';

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
            $checkDailyUpdatedInventory = Inventory::whereDate('updated_at', Carbon::today())->get();

            if (count($checkDailyUpdatedInventory) > 0) {
                $userEmail = [
                    'admin@satrianusa.group',
                    'ops@satrianusa.group',
                    'antony@satrianusa.group',
                ];

                Mail::to($userEmail)->send(new InventoryMail($checkDailyUpdatedInventory));
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
