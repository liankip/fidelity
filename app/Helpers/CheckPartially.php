<?php

namespace App\Helpers;

use App\Models\PurchaseOrder;

class CheckPartially
{
    public static function get(PurchaseOrder $po)
    {
        return $po->pr?->partially;
    }
}
