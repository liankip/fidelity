<?php

namespace Database\Seeders;

use App\Models\IdxPaymentSubmission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IdxPaymentSubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IdxPaymentSubmission::create([
            'idx' => 1,
        ]);
    }
}
