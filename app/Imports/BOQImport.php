<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class BOQImport implements ToCollection, WithCalculatedFormulas, SkipsEmptyRows
{
    /**
     * @param Collection $collection
     */

public function collection(Collection $collection)
    {
    }
}
