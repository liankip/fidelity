<?php

namespace App\Http\Controllers;

use App\Exports\InventoryOutExport;
use App\Models\Project;
use Maatwebsite\Excel\Facades\Excel;

class InventoryOutExportController extends Controller
{
    public function export($paramId)
    {
        $projectName = Project::find($paramId)->name;
        $currentDate = date('d-m-Y');
        $fileName = 'inventory-out-' . $projectName . '-' . $currentDate . '.xlsx';

        return Excel::download(new InventoryOutExport($paramId), $fileName);
    }
}
