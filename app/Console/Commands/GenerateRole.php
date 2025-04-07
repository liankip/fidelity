<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generating roles';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return Command::SUCCESS;
    }
}
