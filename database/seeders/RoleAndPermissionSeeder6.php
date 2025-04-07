<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder6 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            \App\Permissions\Permission::PRINT_VOUCHER,
            \App\Permissions\Permission::CREATE_VOUCHER,
        ];

        $managerRole = Role::findByName('manager');
        $itRole = Role::findByName('it');

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
            $managerRole->givePermissionTo($permission);
            $itRole->givePermissionTo($permission);
        }
    }
}
