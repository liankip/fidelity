<?php

namespace App\Http\Livewire\Permission;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class ManagePermission extends Component
{
    public $selectedUser;
    public $allPermissions;
    public $checkPermissions = [];

    public function mount($userId)
    {
        $this->selectedUser = User::find($userId);
        $this->allPermissions = Permission::all();

        foreach ($this->allPermissions as $permission) {
            if ($this->selectedUser->hasPermissionTo($permission->id)) {
                $this->checkPermissions[$permission->id] = true;
            }
        }
    }

    public function render()
    {
        return view('livewire.permission.manage-permission');
    }

    public function submit()
    {
        $checkedPermissions = [];
        $unCheckedPermissions = [];

        foreach ($this->checkPermissions as $key => $value) {
            $permission = Permission::find($key);
            if (!$value) {
                $unCheckedPermissions[] = $permission->id;
            } else {
                $checkedPermissions[] = $permission->id;
            }
        }

        $this->selectedUser->revokePermissionTo(Permission::whereIn('id', $unCheckedPermissions)->pluck('id'));
        $this->selectedUser->syncPermissions(Permission::whereIn('id', $checkedPermissions)->pluck('id'));
        $this->emit('closeModal');
    }

}
