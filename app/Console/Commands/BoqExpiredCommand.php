<?php

namespace App\Console\Commands;

use App\Mail\BoqExpiredMail;
use App\Models\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BoqExpiredCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:boq-expired-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'BOQ Expired Command';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $projects = Project::with('boqs', 'purchaseRequests')
                ->where('status', 'On going')
                ->pastOneMonth()
                ->get();

            $projectDetails = $projects->map(function ($project) {
                return ['name' => $project->name];
            })->toArray();

            if (!empty($projectDetails)) {
                Mail::to([
                    'antony@satrianusa.group',
                    'anton@satrianusa.group'
                ])->send(new BoqExpiredMail($projectDetails));
                echo "Email sent successfully";
            } else {
                echo "No project found";
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
