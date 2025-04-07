<?php

namespace App\Console\Commands;

use App\Mail\TaskStartMail;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TaskStartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:task-start-command';

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
            $getTask = Task::whereBetween('start_date', [Carbon::today(), Carbon::today()->addDay(14)])->get();

            if (count($getTask) > 0) {
                $userEmail = [
                    'admin@satrianusa.group',
                    'ops@satrianusa.group',
                    'antony@satrianusa.group',
                    'feli@satrianusa.group',
                    'joshua.arief@satrianusa.group',
                    'hari.irawan@satrianusa.group'
                ];

                Mail::to($userEmail)->send(new TaskStartMail($getTask));
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
