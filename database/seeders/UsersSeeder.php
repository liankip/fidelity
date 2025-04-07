<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            // [
            //    'name'=>'Admin User',
            //    'email'=>'admin@sne.com',
            //    'type'=>1,
            //    'password'=> \Hash::make("123456"),
            // ],
            // [
            //    'name'=>'Manager User',
            //    'email'=>'manager@sne.com',
            //    'type'=> 5,
            //    'password'=> \Hash::make("123456"),
            // ],
            // [
            //    'name'=>'User',
            //    'email'=>'user@sne.com',
            //    'type'=>0,
            //    'password'=> \Hash::make("123456"),
            // ],

            [
                'name'=>'antony',
                'email'=>'antony@sne.com',
                'type'=>2,
                'password'=> \Hash::make("123456"),
            ],
            [
                'name'=>'tamuji',
                'email'=>'tamuji@sne.com',
                'type'=>2,
                'password'=> \Hash::make("123456"),
            ],
            [
                'name'=>'feli',
                'email'=>'feli@sne.com',
                'type'=>2,
                'password'=> \Hash::make("123456"),
            ],
            [
                'name'=>'anton',
                'email'=>'anton@sne.com',
                'type'=>2,
                'password'=> \Hash::make("123456"),
            ],

            // [
            //     'name'=>'Purchasing2',
            //     'email'=>'purchasing2@sne.com',
            //     'type'=>3,
            //     'password'=> \Hash::make("123456"),
            // ],
            // [
            //     'name'=>'Finance1',
            //     'email'=>'finance1@sne.com',
            //     'type'=>4,
            //     'password'=> \Hash::make("123456"),
            // ],
            // [
            //     'name'=>'Finance2',
            //     'email'=>'finance2@sne.com',
            //     'type'=>4,
            //     'password'=> \Hash::make("123456"),
            // ],
            // [
            //     'name'=>'IT',
            //     'email'=>'it@sne.com',
            //     'type'=>5,
            //     'password'=> \Hash::make("123456"),
            //  ],
            //  [
            //     'name'=>'Lapangan',
            //     'email'=>'lapangan@sne.com',
            //     'type'=>6,
            //     'password'=> \Hash::make("123456"),
            //  ],
            //  [
            //     'name'=>'AdminLapangan1',
            //     'email'=>'adminlapangan1@sne.com',
            //     'type'=>7,
            //     'password'=> \Hash::make("123456"),
            //  ],
            //  [
            //     'name'=>'AdminLapangan2',
            //     'email'=>'adminlapangan2@sne.com',
            //     'type'=>7,
            //     'password'=> \Hash::make("123456"),
            //  ],
            //  [
            //     'name'=>'Mayang',
            //     'email'=>'mayang@sne.com',
            //     'type'=>7,
            //     'password'=> \Hash::make("123456"),
            //  ],
            //  [
            //     'name'=>'Retno',
            //     'email'=>'retno@sne.com',
            //     'type'=>4,
            //     'password'=> \Hash::make("123456"),
            // ],
            // [
            //     'name'=>'Anggi',
            //     'email'=>'anggi@sne.com',
            //     'type'=>4,
            //     'password'=> \Hash::make("123456"),
            // ],
            // [
            //     'name'=>'Sari',
            //     'email'=>'sari@sne.com',
            //     'type'=>3,
            //     'password'=> \Hash::make("123456"),
            // ],
            // [
            //     'name'=>'Janio',
            //     'email'=>'janio@sne.com',
            //     'type'=>5,
            //     'password'=> \Hash::make("123456"),
            // ],
            // [
            //     'name'=>'Antony',
            //     'email'=>'antony@sne.com',
            //     'type'=>5,
            //     'password'=> \Hash::make("123456"),
            // ],


        ];

        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}
