<?php

namespace App\Helpers;

use App\Roles\Role;

class UserRoles
{
    public static function getColorBadge($role): string
    {
        return match ($role) {
            Role::ADMIN => 'bg-primary',
            Role::ADMIN_2 => 'bg-secondary',
            Role::IT, Role::MANAGER, Role::SUPER_ADMIN, Role::TOP_MANAGER, Role::TIER_1, Role::TIER_2, Role::TIER_3, Role::TIER_4, Role::TIER_5 => 'bg-danger',
            Role::ADMIN_LAPANGAN, Role::ADMIN_GUDANG, Role::PAYABLE => 'bg-warning',
            Role::LAPANGAN => 'bg-info',
            Role::K3 => 'bg-success',
            Role::FINANCE, Role::PURCHASING => 'bg-success',
            default => 'bg-yellow',
        };
    }
}
