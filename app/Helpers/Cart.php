<?php

    namespace App\Helpers;

use App\Models\Item;

    class Cart
    {
        public function __construct()
        {
            if($this->get === null)
            {
                $this->set($this->empty());
            }
        }
        public function add(Item $item)
        {
            $cart = $this->get();

            array_push($cart['items'], $item);

            $this->set($cart);
        }
        public function empty()
        {
            return [
                'items' => []
            ];
        }
        public function get()
        {
            return session()->get('cart');
        }
        public function set($cart)
        {
            
        }


    }
?>