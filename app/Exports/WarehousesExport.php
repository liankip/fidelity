<?php

namespace App\Exports;

use App\Models\Warehouse;
use Maatwebsite\Excel\Concerns\FromCollection;

class WarehousesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return Warehouse::all();
        return Warehouse::select("name", "pic", "email", "phone", "address", "city", "province", "post_code")->get();
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Name", "PIC", "Email", "Phone", "Address", "City", "Province", "Post Code"];
    }
}
