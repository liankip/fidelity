<?php

namespace Database\Seeders;
use App\Models\CompanyDetail;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        CompanyDetail::create([
            'name' => 'Satria Nusa Engineering',
            'pic' => 'Anthony',
            'email' => 'test@sne.com',
            'phone' => '081234567890',
            'address' => 'Jl. Kartini II no.11',
            'city' => 'Medan',
            'province' => 'Sumatera Utara',
            'post_code' => '20717',
            'npwpd' => '-',
            'signature' => '-',
            'created_by' => '8',
        ]);
    }
}
