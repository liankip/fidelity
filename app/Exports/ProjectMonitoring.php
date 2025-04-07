<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ProjectMonitoring implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public $items;
    public function __construct($project_id)
    {
        $this->items = $project_id;
        // dd($this->items);
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->items;
    }

    public function map($row): array
    {
        return [
            // $row->no,
            $row['no'],
            $row['name'],
            $row['qty'],
            $row['unit_name'],
            $row['qty_pr'],
            $row['qty_po'],
            $row['amount_po'],
        ];
    }
    public function headings(): array
    {
        return ["no","Name", "qty", "unit_name", "qty_pr", "qty_po", "amount_po"];
    }
    public function styles($sheet)
    {
        $sheet->getStyle('A1:G1')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'B8860B',
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => [
                            'rgb' => '000000',
                        ],
                    ],
                ],
            ],
        ]);
    }
}
