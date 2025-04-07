<?php

namespace Database\Seeders;
use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Item 1
        Item::create([
            'item_code' => 'A01',
            'name' => 'Watch 1',
            'type' => 200,
            'unit' => 'Apple watch',
            'image' => 'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=750&q=80'
        ]);
        Item::create([
            'item_code' => 'A02',
            'name' => 'Bag 1',
            'type' => 100,
            'unit' => 'Apple Bag',
            'image' => 'https://images.unsplash.com/photo-1554342872-034a06541bad?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=750&q=80'
        ]);
        Item::create([
            'item_code' => 'B01',
            'name' => 'Perfume 1',
            'type' => 150,
            'unit' => 'Apple Perfume',
            'image' => 'https://images.unsplash.com/photo-1523293182086-7651a899d37f?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=750&q=80'
        ]);
        Item::create([
            'item_code' => 'C01',
            'name' => 'Coffee 1',
            'type' => 100,
            'unit' => 'Cold Coffee',
            'image' => 'https://images.unsplash.com/photo-1568649929103-28ffbefaca1e?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=750&q=80'
        ]);

        //Item 2
        Item::create([
            'item_code' => 'A01',
            'name' => 'Watch 2',
            'type' => 200,
            'unit' => 'Apple watch',
            'image' => 'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=750&q=80'
        ]);
        Item::create([
            'item_code' => 'A02',
            'name' => 'Bag 2',
            'type' => 100,
            'unit' => 'Apple Bag',
            'image' => 'https://images.unsplash.com/photo-1554342872-034a06541bad?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=750&q=80'
        ]);
        Item::create([
            'item_code' => 'B01',
            'name' => 'Perfume 2',
            'type' => 150,
            'unit' => 'Apple Perfume',
            'image' => 'https://images.unsplash.com/photo-1523293182086-7651a899d37f?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=750&q=80'
        ]);
        Item::create([
            'item_code' => 'C01',
            'name' => 'Coffee 2',
            'type' => 100,
            'unit' => 'Cold Coffee',
            'image' => 'https://images.unsplash.com/photo-1568649929103-28ffbefaca1e?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=750&q=80'
        ]);

        //Item 3
        Item::create([
            'item_code' => 'A01',
            'name' => 'Watch 3',
            'type' => 200,
            'unit' => 'Apple watch',
            'image' => 'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=750&q=80'
        ]);
        Item::create([
            'item_code' => 'A02',
            'name' => 'Bag 3',
            'type' => 100,
            'unit' => 'Apple Bag',
            'image' => 'https://images.unsplash.com/photo-1554342872-034a06541bad?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=750&q=80'
        ]);
        Item::create([
            'item_code' => 'B01',
            'name' => 'Perfume 3',
            'type' => 150,
            'unit' => 'Apple Perfume',
            'image' => 'https://images.unsplash.com/photo-1523293182086-7651a899d37f?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=750&q=80'
        ]);
        Item::create([
            'item_code' => 'C01',
            'name' => 'Coffee 3',
            'type' => 100,
            'unit' => 'Cold Coffee',
            'image' => 'https://images.unsplash.com/photo-1568649929103-28ffbefaca1e?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=750&q=80'
        ]);
    }
}
