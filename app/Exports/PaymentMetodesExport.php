<?php

namespace App\Exports;

use App\Models\PaymentMetode;
use Maatwebsite\Excel\Concerns\FromCollection;

class PaymentMetodesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PaymentMetode::all();
    }
}
