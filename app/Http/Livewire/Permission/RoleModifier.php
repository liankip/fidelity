<?php

namespace App\Http\Livewire\Permission;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleModifier extends Component
{
    public $selectedUser;
    public $roles;
    public $roleChecked = [];
    public $listeners = ['changeRole'];

    public function mount($userId)
    {
        $this->selectedUser = User::find($userId);
        $this->roles = Role::where('name', '!=', 'super-admin')->get();
        foreach ($this->roles as $role) {
            if ($this->selectedUser->getRoleNames()->contains($role->name)) {
                $this->roleChecked[$role->id] = true;
            }
        }

    }

    public function render()
    {
        return view('livewire.permission.role-modifier');
    }

    public function changeRole($data)
    {
        $roles = Role::whereIn('id', $data)->pluck('name');
        $this->selectedUser->syncRoles($roles);
    }

    public function submit()
    {
        $checkedRoles = [];

        foreach ($this->roleChecked as $key => $value) {
            $role = Role::find($key);
            if (!$value) {
                $this->selectedUser->removeRole($role->name);
            } else {
                $checkedRoles[] = $role->id;
            }
        }

        $this->selectedUser->syncRoles(Role::whereIn('id', $checkedRoles)->pluck('name'));
        $this->emitTo('user-management', 'updateUser');
    }

}
