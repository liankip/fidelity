<?php

namespace App\Http\Livewire\Common;

use Livewire\Component;

class Modal extends Component
{
    public $component;
    public $activemodal;

    protected $listeners = ['openModal', 'closeModal'];

    public function openModal($component) {
        $this->component = $component;
        $this->activemodal = rand();
        $this->dispatchBrowserEvent('showBootstrapModal');
    }

    public function closeModal() {
        $this->activemodal = null;
        $this->dispatchBrowserEvent('closeBootstrapModal');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.common.modal');
    }
}
