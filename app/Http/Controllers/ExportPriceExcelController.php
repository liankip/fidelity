<?php

namespace App\Http\Controllers;

use App\Exports\PricesExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportPriceExcelController extends Controller
{
    public function export()
    {
        $fileName = 'prices-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new PricesExport, $fileName);
    }
}
