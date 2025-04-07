<?php

namespace App\Http\Livewire\UserManagement;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class CreateUserModal extends Component
{
    public $roles;

    public $name, $username, $email, $phone_number, $password, $role;

    public function mount()
    {
        $this->roles = Role::where('name', '!=', 'super-admin')->get();
    }

    public function render()
    {
        return view('livewire.user-management.create-user-modal');
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'phone_number' => 'nullable',
        ]);

        $role = Role::find($this->role);
        $user = User::create([
            'name' => $this->name,
            'username' => $this->username,
            'type' => User::typeNumber($role->name),
            'is_disabled' => false,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'phone_number' => $this->phone_number,
            'boq_verificator' => $role->name === \App\Roles\Role::MANAGER ? 1 : 0,
        ]);

        $user->assignRole($role);

        return redirect()->route('user-management')->with('success', 'User berhasil ditambahkan');
    }
}
