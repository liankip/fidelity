<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserManagement extends Component
{
    public $disabledUsers;
    public $selectedUser;

    public $listeners = ['attachPermissionRole', 'detachPermissionRole', 'updateUser'];
    public $checkPermissions = [];
    public $permissions;
    public $roles;
    public $search;

    public function mount()
    {
        $this->disabledUsers = User::where('is_disabled', true)->get();
        $this->roles = Role::where('name', '!=', 'super-admin')->get();
        $this->permissions = Permission::all();
    }

    public function render()
    {
        $users = User::activeUser()->when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('username', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhere('phone_number', 'like', '%' . $this->search . '%');
        })->orderByDesc('created_at')->get();

        return view('livewire.user-management', compact('users'));
    }

    public function updateUser()
    {
        $this->render();
    }

    public function openPermissionModal($id)
    {
        $this->emit('openModal', [
            'name' => 'permission.manage-permission',
            'arguments' => [
                'userId' => $id,
            ]
        ]);
    }

    public function openCreateUserModal()
    {
        $this->emit('openModal', [
            'name' => 'user-management.create-user-modal',
            'arguments' => []
        ]);
    }

    public function openRoleModal($id)
    {
        $this->emit('openModal', [
            'name' => 'permission.role-modifier',
            'arguments' => [
                'userId' => $id,
            ]
        ]);
    }

    public function attachPermissionRole($roleId, $permissionId)
    {
        $role = Role::find($roleId);
        $permission = Permission::find($permissionId);
        $role->givePermissionTo($permission);
    }

    public function detachPermissionRole($roleId, $permissionId)
    {
        $role = Role::find($roleId);
        $permission = Permission::find($permissionId);
        $role->revokePermissionTo($permission);
    }

    public function activateUser($id)
    {
        $user = User::find($id);
        $user->is_disabled = false;
        $user->save();

        return redirect()->route('user-management')->with('success', 'User berhasil diaktifkan');
    }

    public function deactivateUser($id)
    {
        $user = User::find($id);
        $user->is_disabled = true;
        $user->save();

        return redirect()->route('user-management')->with('success', 'User berhasil dinonaktifkan');
    }
}
