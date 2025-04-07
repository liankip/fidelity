<?php

namespace App\Imports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Hash;

class ItemsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Item([
            //
            'item_code' => $row['item_code'],
            'name' => $row['name'],
            'type' => $row['type'],
            'unit' => $row['unit'],
            'created_by' => Auth::user()->id,
            'image' => 'images/no_image.png',
        ]);
    }
}
