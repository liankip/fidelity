<?php

namespace App\Exports;

use App\Models\EventType;
use Maatwebsite\Excel\Concerns\FromCollection;

class EventTypesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return EventType::all();
        return EventType::select("type", "remark")->get();
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Type", "Remark"];
    }
}
