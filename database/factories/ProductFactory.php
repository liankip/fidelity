<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = \App\Models\Product::class;

    public function definition()
    {
        $items = [];
        $ids = range(9, 30);
        shuffle($ids);

        for ($i = 0; $i < 14; $i++) {
            $items[] = [
                (string) array_pop($ids),
                $this->faker->randomElement(['Pcs', 'Kotak', 'Unit', 'set', 'meter']),
                $this->faker->numberBetween(9000, 1400000),
                $this->faker->numberBetween(1, 100),
                0,
                null,
            ];
        }

        return [
            'nama' => $this->faker->name,
            'data' => json_encode($items),  // Convert array to JSON string
        ];
    }
}
