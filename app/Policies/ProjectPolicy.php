<?php

namespace App\Policies;

use App\Models\User;
use App\Permissions\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(User $user) {
        return $user->can(Permission::CREATE_PROJECT);
    }
}
