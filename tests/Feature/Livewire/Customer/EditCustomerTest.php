<?php

namespace Tests\Feature\Livewire\Customer;

use App\Http\Livewire\Customer\EditCustomer;
use App\Models\Customer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class EditCustomerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    /** @test */
    public function GivenRequest_WhenParamsIsFill_ThenDataHasBeenEdit()
    {
        $customer = Customer::factory()->create();

        $newData = [
            'nama' => $this->faker->name,
            'nomor_handphone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'alamat' => $this->faker->address,
            'provinsi' => $this->faker->state,
            'kota' => $this->faker->city,
        ];

        Livewire::test(EditCustomer::class, ['customer' => $customer->id])
            ->set('nama', $newData['nama'])
            ->set('nomor_handphone', $newData['nomor_handphone'])
            ->set('email', $newData['email'])
            ->set('alamat', $newData['alamat'])
            ->set('provinsi', $newData['provinsi'])
            ->set('kota', $newData['kota'])
            ->call('edit');

        $this->assertDatabaseHas('customer', $newData);
    }
}
