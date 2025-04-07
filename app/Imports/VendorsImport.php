<?php

namespace App\Imports;

use App\Models\Vendor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Hash;

class VendorsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Vendor([
            'name' => $row['name'],
            'pic' => $row['pic'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'address' => $row['address'],
            'city' => $row['city'],
            'province' => $row['province'],
            'post_code' => $row['post_code'],
            'created_by' => $row['created_by'],
        ]);
    }
}
