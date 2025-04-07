<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryItemSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('category_items')->insert([
            [
                'name' => 'CHEMICAL',
                'created_at' => now(),
            ],
            [
                'created_at' => now(),
                'name' => 'JASA',
            ],
            [
                'name' => 'MESIN',
                'created_at' => now(),
            ],
            [
                'name' => 'UMUM',
                'created_at' => now(),
            ],
        ]);

    }
}
