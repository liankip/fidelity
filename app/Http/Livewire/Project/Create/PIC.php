<?php

namespace App\Http\Livewire\Project\Create;

use App\Models\User;
use Livewire\Component;

class PIC extends Component
{
    public $fillManual = false;
    public $users;
    public $user;
    public $userData = [];

    public $listeners = ['userSelected'];

    public function mount($userData)
    {
        $this->users = User::where('is_disabled', false)->get();
        $this->userData = $userData;
    }

    public function render()
    {
        return view('livewire.project.create.p-i-c');
    }
}
