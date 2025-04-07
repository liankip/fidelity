<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'payable',
                'guard_name' => 'web',
                'created_at' => NOW(),
                'updated_at' => NOW()
            ],
        ];

        DB::table('roles')->insert($roles);
    }
}
