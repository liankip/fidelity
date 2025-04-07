<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder4 extends Seeder
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
            'approve-supplier'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $managerRole = Role::findByName('manager');
        $itRole = Role::findByName('it');

        $managerRole->givePermissionTo('approve-supplier');
        $itRole->givePermissionTo('approve-supplier');
    }
}
