<?php

namespace App\Providers;

use Cart;
use Illuminate\Support\ServiceProvider;

class CartFacadeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        // App::bind('cart', function(){
        //     return new Cart;
        // });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
