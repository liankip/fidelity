<?php

namespace App\Helpers;

class PurchaseOrderUtils
{
    public static function getPoNumber($po_no)
    {
        $po_no = explode('/', $po_no);

        if (isset($po_no[0])) {
            return $po_no[0];
        }

        return "";
    }

    public static function getEmailSubject($po_no)
    {
        return "Purchase Order with No. PO " . $po_no;
    }
}
