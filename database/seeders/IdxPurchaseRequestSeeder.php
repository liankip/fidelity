<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IdxPurchaseRequest;

class IdxPurchaseRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IdxPurchaseRequest::create([
            'idx' => 111,
        ]);
    }
}
