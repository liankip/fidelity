<?php

namespace App\Imports;

use App\Models\CompanyDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Hash;

class CompanyDetailsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new CompanyDetail([
            //
            'name' => $row['name'],
            'pic' => $row['pic'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'address' => $row['address'],
            'city' => $row['city'],
            'province' => $row['province'],
            'post_code' => $row['post_code'],
            'npwpd' => $row['npwpd'],
            'signature' => $row['signature'],
            'created_by' => $row['created_by'],
        ]);
    }
}
