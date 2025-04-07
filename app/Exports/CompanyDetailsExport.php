<?php

namespace App\Exports;

use App\Models\CompanyDetail;
use Maatwebsite\Excel\Concerns\FromCollection;

class CompanyDetailsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return CompanyDetail::all();
        return CompanyDetail::select("name", "pic", "email", "phone", "address", "city", "province", "post_code")->get();
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
