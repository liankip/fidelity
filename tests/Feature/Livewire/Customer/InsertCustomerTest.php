<?php

namespace Tests\Feature\Livewire\Customer;

use App\Http\Livewire\Customer\InsertCustomer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class InsertCustomerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    /** @test */
    public function GivenRequest_WhenParamsIsFill_ThenDataHasInsertAsSameFill()
    {
        $customerData = [
            'nama' => $this->faker->name,
            'nomor_handphone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'alamat' => $this->faker->address,
            'provinsi' => $this->faker->state,
            'kota' => $this->faker->city,
        ];

        Livewire::test(InsertCustomer::class)
            ->set('nama', $customerData['nama'])
            ->set('nomor_handphone', $customerData['nomor_handphone'])
            ->set('email', $customerData['email'])
            ->set('alamat', $customerData['alamat'])
            ->set('provinsi', $customerData['provinsi'])
            ->set('kota', $customerData['kota'])
            ->call('insert');

        $this->assertDatabaseHas('customer', $customerData);
    }
}
