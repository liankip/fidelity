<?php

namespace Tests\Feature\Livewire\Product;

use App\Http\Livewire\Product\Product;
use App\Models\Product as ModelProduct;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function GivenRequest_WhenListDataHasFirstData_ThenListDataShowFirstData()
    {
        $product = \App\Models\Product::factory()->create();

        Livewire::test(Product::class)
                ->assertSee(ModelProduct::first()->nama);
    }

    /** @test */
    public function GivenRequest_WhenParamSearchIsFill_ThenDataShowFirstDataNotSecondData()
    {
        $customer1 = ModelProduct::factory()->create();
        $customer2 = ModelProduct::factory()->create();

        Livewire::test(Product::class)
            ->set('search', $customer1->nama)
            ->assertSee($customer1->nama)
            ->assertDontSee($customer2->nama);
    }
}
