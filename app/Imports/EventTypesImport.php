<?php

namespace App\Imports;

use App\Models\EventType;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Hash;

class EventTypesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new EventType([
            //
            'type' => $row['type'],
            'remark' => $row['remark'],
            'created_by' => $row['created_by'],
        ]);
    }
}
