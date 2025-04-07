<?php

namespace App\Http\Livewire\Common;

use Livewire\Component;

class Alert extends Component
{
    public $listeners = ['showAlert'];

    public function render()
    {
        return view('livewire.common.alert');
    }

    public function showAlert($data)
    {
        session()->flash($data['type'], $data['message']);
    }
}
