<?php

use Illuminate\Support\Facades\Facade;

Class Cart extends Facade
{
    public static function getFacadeAccessor(){
        return 'cart';
    }
}

?>