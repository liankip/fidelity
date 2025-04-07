<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ApprovePRPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permission = [
            \App\Permissions\Permission::APPROVE_PR,
        ];

        $managerRole = Role::findByName('manager');
        $itRole = Role::findByName('it');

        foreach ($permission as $permission) {
            Permission::create(['name' => $permission]);
            $managerRole->givePermissionTo($permission);
            $itRole->givePermissionTo($permission);
        }
    }
}
