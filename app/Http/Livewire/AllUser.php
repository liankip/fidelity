<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;


class AllUser extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'super-admin');
            })
            ->where('active', 1)
            ->orderBy('name', 'asc')
            ->paginate(15);

        return view('livewire.all-user', [
            'users' => $users
        ]);
    }
}
