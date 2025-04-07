<?php

namespace App\Console\Commands;

use App\Permissions\PermissionList;
use Illuminate\Console\Command;
use Spatie\Permission\Contracts\Permission as PermissionContract;

class GeneratePermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generating permissions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $permissions = PermissionList::getLists();
        $permissionClass = app(PermissionContract::class);

        $permissionCreated = 0;
        foreach ($permissions as $permission) {
            $permissionExists = $permissionClass::where('name', $permission)->first();

            if (!$permissionExists) {
                $permissionClass::create(['name' => $permission]);
                $this->info("Permission `{$permission}` created");
                $permissionCreated++;
            }
        }

        if ($permissionCreated == 0) {
            $this->info("No permission created");
        }

    }
}
