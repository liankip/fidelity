<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorItem extends Model
{
    protected $fillable = [
        'item_name',
        'price',
        'brand',
        'unit_id',
        'category_id',
        'type',
        'vendor_id',
        'certificate',
        'item_notes'
    ];

    public function vendor()
    {
        return $this->belongsTo(VendorRegistrant::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function category()
    {
        return $this->belongsTo(CategoryItem::class);
    }

    public function getFormattedPriceAttribute()
    {
        return rupiah_format($this->price);
    }

    public static function approved()
    {
        return self::whereHas('vendor', function ($query) {
            $query->where('is_approved', 1);
        });
    }
}
