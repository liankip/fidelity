<?php

namespace Tests\Feature\Livewire\Customer;

use App\Http\Livewire\Customer\Customer;
use App\Models\Customer as ModelCustomer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function GivenRequest_WhenListHasData_ThenListHasData()
    {
        ModelCustomer::factory(30)->create();

        Livewire::test(Customer::class)
            ->assertSee(ModelCustomer::first()->nama)
            ->assertSee(ModelCustomer::first()->email);
    }

    /** @test */
    public function GivenRequest_WhenParamCustomerIsFill_ThenDataHasBeenDelete()
    {
        Livewire::test(Customer::class)
            ->call('delete', ['id' => 1])
            ->assertRedirect(route('customer.index'));
    }

    /** @test */
    public function GivenRequest_WhenParamsSearchIsFill_ThenDataShowJhon()
    {
        $customer1 = ModelCustomer::factory()->create();
        $customer2 = ModelCustomer::factory()->create();

        Livewire::test(Customer::class)
            ->set('search', $customer1->nama)
            ->assertSee($customer1->nama)
            ->assertDontSee($customer2->nama);
    }
}
