<?php

namespace App\Http\Livewire\Suppliers;

use Livewire\Component;

class VendorRegistration extends Component
{
    public function render()
    {
        return view('livewire.suppliers.vendor-registration')->extends('layouts.guest')->section('content');
    }
}
