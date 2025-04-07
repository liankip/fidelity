<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'items';
    protected $fillable = [
        'item_code',
        'name',
        'type',
        'brand',
        'unit',
        'created_by',
        'image',
        'notes_k3',
        'is_approved',
        'category_id',
        'approved_by',
        'is_disabled',
        'file_upload',
        'rfa'
    ];

    public static function available()
    {
        return self::where('is_disabled', 0)->whereNull('deleted_at')->where('is_approved', 1);
    }

    public static function removed()
    {
        return self::where('is_disabled', 1);
    }


    public function item_created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function item_unit()
    {
        return $this->hasMany(ItemUnit::class, 'item_id', 'id');
    }
    public function pr()
    {
        return $this->hasMany(PurchaseRequest::class,"item_id","id");
    }

    public function category()
    {
        return $this->belongsTo(CategoryItem::class, 'category_id');
    }

    public function itemPrice()
    {
        return $this->hasMany(SupplierItemPrice::class, 'item_id', 'id');
    }

    public static function historyPrices()
    {
        $items = self::available()->get();
        $historyPrices = DB::table('purchase_order_details')
            ->join('purchase_orders', 'purchase_orders.id', '=', 'purchase_order_details.purchase_order_id')
            ->where('purchase_orders.status', '=', 'Approved')
            ->whereIn('purchase_order_details.item_id', $items->pluck('id'))
            ->orderBy('purchase_order_details.price', 'asc')
            ->select('purchase_order_details.price', 'purchase_order_details.item_id')
            ->get();

        return $historyPrices;
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
