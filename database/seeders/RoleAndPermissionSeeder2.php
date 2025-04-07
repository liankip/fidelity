<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder2 extends Seeder
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
            'manage_group'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $managerRole = Role::findByName('manager');
        $itRole = Role::findByName('it');

        $managerRole->givePermissionTo('manage_group');
        $itRole->givePermissionTo('manage_group');
    }
}
