<?php

namespace App\Exports;

use App\Models\DeliveryService;
use Maatwebsite\Excel\Concerns\FromCollection;

class DeliveryServicesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return DeliveryService::all();
        return DeliveryService::select("name", "ground")->get();
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Name", "Ground"];
    }
}
