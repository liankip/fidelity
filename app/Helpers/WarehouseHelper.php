<?php

namespace App\Helpers;

use App\Models\Warehouse;

class WarehouseHelper
{
    /**
     * Get warehouses based on projectId condition
     *
     * @param int|null $projectId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getFilteredWarehouses($projectId, $isRawMaterial = false)
    {
        $query = Warehouse::whereNull("deleted_at");

        if($isRawMaterial) {
            $query->whereNotIn("name", ["Gudang Medan", "Gudang Jakarta"]);
        }

        if ($projectId === null && !$isRawMaterial) {
            $query->whereIn("name", ["Gudang Medan", "Gudang Jakarta"]);
        }

        return $query->get();
    }
}
