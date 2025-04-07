<?php

namespace App\Imports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
// use Maatwebsite\Excel\Concerns\ToCollection;
use Hash;
class suppliersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Supplier([
            //
            'name' => $row['name'],
            'pic' => $row['pic'],
            'term_of_payment' => $row['term_of_payment'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'address' => $row['address'],
            'city' => $row['city'],
            'province' => $row['province'],
            'post_code' => $row['post_code'],
            'created_by' => Auth::user()->id,
        ]);
    }
}
