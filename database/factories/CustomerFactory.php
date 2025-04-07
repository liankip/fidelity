<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = \App\Models\Customer::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->name,
            'nomor_handphone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'alamat' => $this->faker->address,
            'provinsi' => $this->faker->state,
            'kota' => $this->faker->city,
        ];
    }
}
