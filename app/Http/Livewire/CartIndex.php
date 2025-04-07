<?php

namespace App\Http\Livewire;
use App\Models\Item;
use Livewire\Component;

class CartIndex extends Component
{
    public function render()
    {
        return view('livewire.cart-index');
    }
}
