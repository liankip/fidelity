<?php

namespace App\Policies;

use App\Models\User;
use App\Roles\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseOrderPolicy
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

    public function show(User $user)
    {
        return $user->hasAnyRole([
            Role::IT,
            Role::PURCHASING,
            Role::MANAGER
        ]);
    }

    public function edit(User $user)
    {
        return $user->hasAnyRole([
            Role::IT,
            Role::PURCHASING,
            Role::MANAGER
        ]);
    }


}
