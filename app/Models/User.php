<?php

namespace App\Models;

use App\Roles\Role;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array

     */
    protected $fillable = [
        'nik',
        'position',
        'education',
        'status',
        'gender',
        'dob',
        'accepted_date',
        'address',
        'disability',
        'active',
        'name',
        'email',
        'password',
        'type',
        'is_disabled',
        'username',
        'phone_number',
        'boq_verificator',
        'tier',
        'contract_no'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array

     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array

     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Interact with the user's first name.
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function type(): Attribute
    {
        return new Attribute(
            get: fn($value) => ["user", "admin", "manager", "top-manager", "purchasing", "finance", "it", "lapangan", "adminlapangan", "admin_2", "vendor", "admin-gudang", "payable"][$value],
        );
    }

    public function notification()
    {
        return $this->hasMany(Notification::class, "notifiable_id", "id");
    }

    public function purchase_requests()
    {
        return $this->belongsToMany(PurchaseRequest::class, "purchase_request_user");
    }

    public function hasTopLevelAccess()
    {
        return $this->hasRole([Role::MANAGER, Role::IT, ROLE::TOP_MANAGER]);
    }

    public function hasK3LevelAccess()
    {
        return $this->hasRole([Role::K3]);
    }

    public function hasAdminLapanganLevelAccess()
    {
        return $this->hasRole([Role::ADMIN_LAPANGAN]);
    }

    public function hasTopManagerAccess()
    {
        return $this->hasRole([Role::TOP_MANAGER, Role::IT]);
    }

    public function hasGeneralAccess()
    {
        return !$this->hasRole(Role::ADMIN_2);
    }

    public function hasTier1Access()
    {
        return $this->hasRole(Role::TIER_1);
    }

    public function hasTier2Access()
    {
        return $this->hasRole(Role::TIER_2);
    }

    public function hasTier3Access()
    {
        return $this->hasRole(Role::TIER_3);
    }

    public function hasTier4Access()
    {
        return $this->hasRole(Role::TIER_4);
    }

    public function hasTier5Access()
    {
        return $this->hasRole(Role::TIER_5);
    }

    public function hasPRAccess()
    {
        return in_array($this->type, ["manager", "adminlapangan", "it", "lapangan"]);
    }

    public function hasApproveBOQSpreadsheet()
    {
        return $this->hasPermissionTo('approve-boq-spreadsheet');
    }

    public static function typeNumber(string $type): int
    {
        $types = ["user", "admin", "manager", "purchasing", "finance", "it", "lapangan", "adminlapangan", "admin_2", 'vendor'];
        $index = array_search($type, $types);
        return $index !== false ? $index : 1;
    }

    public static function withoutRole($role)
    {
        if (is_array($role)) {
            return self::role(\Spatie\Permission\Models\Role::whereNotIn('name', $role)->get())->get();
        }

        return self::role(\Spatie\Permission\Models\Role::where('name', '!=', $role)->get())->get();
    }

    public static function activeUser()
    {
        return self::query()->where('is_disabled', false);
    }

    public function isActive()
    {
        return $this->active;
    }

    public function isInternalUser()
    {
        return $this->hasRole([Role::ADMIN, Role::ADMIN_2, Role::MANAGER, Role::IT, Role::PURCHASING, Role::FINANCE, Role::ADMIN_LAPANGAN, Role::LAPANGAN]);
    }

    public function vendorRegistrant()
    {
        return $this->hasOne(VendorRegistrant::class);
    }
}
