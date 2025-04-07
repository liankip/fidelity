<?php

namespace App\Imports;

use App\Models\DeliveryService;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Hash;

class DeliveryServicesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new DeliveryService([

            'name' => $row['name'],
            'ground' => $row['ground'],
            'created_by' => $row['created_by']
        ]);
    }
}
