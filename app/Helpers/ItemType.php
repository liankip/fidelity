<?php

namespace App\Helpers;

class ItemType
{
    public static function get(): array
    {
        return [
            "inv" => "Persedian",
            "non" => "Non Persedian",
            "svc" => "Jasa",
            "grp" => "Group",
            "pdc" => "Biaya Produksi"
        ];
    }
}
