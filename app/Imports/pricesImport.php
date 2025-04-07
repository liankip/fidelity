<?php

namespace App\Imports;

use App\Models\Price;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;

class pricesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Price([
            //
            'supplier_id' => $row['supplier_id'],
            'item_id' => $row['item_id'],
            'price' => $row['price'],
            'tax' => $row['tax'],
            'tax_status' => $row['tax_status'],
            'created_by' => Auth::user()->id,
        ]);
    }
}
