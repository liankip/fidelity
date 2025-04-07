<?php

namespace Database\Seeders;

use App\Models\IdxVoucher;
use Illuminate\Database\Seeder;

class IdxVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IdxVoucher::create([
            'idx' => 1,
        ]);
    }
}
