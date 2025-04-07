<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Http\Livewire\Product\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Schema::disableForeignKeyConstraints();
        $this->call([
            ProductSeeder::class,
            CustomerSeeder::class,
            OrderSeeder::class,
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
