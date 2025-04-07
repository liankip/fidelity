<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorRegistrant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'user_id',
        'npwp_image',
        'npwp',
        'address',
        'ktp_image',
        'email',
        'telp',
        'sales_email',
        'sales_phone',
        'bank_name',
        'account_number',
        'bank_owner_name',
        'bank_branch',
        'top',
        'is_approved',
        'aproved_by',
        'company_profile',
        'product_catalogue',
        'website_link',
    ];

    protected $casts = [
        'top' => 'array',
    ];

    public function documents()
    {
        return $this->hasMany(VendorRegistrantDocument::class);
    }

    public static function needApproval()
    {
        return self::where('is_approved', false);
    }

    public static function approved()
    {
        return self::where('is_approved', true)->get();
    }

    public function items()
    {
        return $this->hasMany(VendorItem::class, 'vendor_id', 'id');
    }
}
