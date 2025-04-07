<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
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
            'create-po',
            'create-pr',
            'create-project',
            'create-boq',
            'approve-po',
            'approve-boq',
            'view-surat-jalan',
            'cancel-po',
            'print-latest-po',
            'duplicate-pr',
            'edit-item-load',
            'ajukan-pr',
            'edit-barang'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }


        $adminRole = Role::create(['name' => 'admin']);
        $admin2Role = Role::create(['name' => 'admin_2']);
        $superAdminRole = Role::create(['name' => 'super-admin']);
        $itRole = Role::create(['name' => 'it']);

        $managerPermission = [
            'create-po',
            'create-pr',
            'create-project',
            'create-boq',
            'approve-po',
            'approve-boq',
            'view-surat-jalan',
            'cancel-po',
            'print-latest-po',
            'duplicate-pr',
            'edit-item-load',
            'ajukan-pr',
            'edit-barang'
        ];

        $managerRole = Role::create(['name' => 'manager']);
        $managerRole->givePermissionTo($managerPermission);
        $itRole->givePermissionTo($managerPermission);

        $purchasingRole = Role::create(['name' => 'purchasing']);
        $purchasingRole->givePermissionTo([
            'print-latest-po',
            'create-po',
        ]);

        $financeRole = Role::create(['name' => 'finance']);
        $financeRole->givePermissionTo([
            'cancel-po'
        ]);

        $lapanganRole = Role::create(['name' => 'lapangan']);
        $lapanganRole->givePermissionTo([
            'view-surat-jalan'
        ]);

        $adminlapanganRole = Role::create(['name' => 'adminlapangan']);
        $adminlapanganRole->givePermissionTo([
            'view-surat-jalan',
            'print-latest-po',
            'ajukan-pr',
            'edit-item-load',
            'edit-barang',
            'create-pr'
        ]);

        $admins = User::where('type', 1)->get();
        foreach ($admins as $admin) {
            $admin->assignRole('admin');
        }

        $managers = User::where('type', 2)->get();
        foreach ($managers as $manager) {
            $manager->assignRole('manager');
        }

        $purchasings = User::where('type', 3)->get();
        foreach ($purchasings as $purchasing) {
            $purchasing->assignRole('purchasing');
        }

        $finances = User::where('type', 4)->get();
        foreach ($finances as $finance) {
            $finance->assignRole('finance');
        }

        $its = User::where('type', 5)->where('email', '!=', 'it@sne.com')->get();
        foreach ($its as $it) {
            $it->assignRole('it');
        }

        $lapangans = User::where('type', 6)->get();
        foreach ($lapangans as $lapangan) {
            $lapangan->assignRole('lapangan');
        }

        $adminlapangans = User::where('type', 7)->get();
        foreach ($adminlapangans as $adminlapangan) {
            $adminlapangan->assignRole('adminlapangan');
        }

        $admins2 = User::where('type', 8)->get();
        foreach ($admins2 as $admin2) {
            $admin2->assignRole('admin_2');
        }

        $it = User::where('email', 'it@sne.com')->first();
        $it->assignRole('super-admin');
        $it->assignRole('it');
    }


}
