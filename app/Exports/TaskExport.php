<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TaskExport implements FromArray, WithHeadings, ShouldAutoSize
{
    /**
     * Return an array of data to be exported.
     *
     * @return array
     */
    public function array(): array
    {
        return [
            ['1', 'Section 1', '1', 'Task 1', '1', '1', '01/08/2024', '2', '3', '10/08/2024'],
            ['', '', '2', 'Task 2', '1', '1', '11/08/2024', '2', '3', '20/08/2024'],
            ['2', 'Section 2', '1', 'Task 3', '1', '1', '21/08/2024', '2', '3', '30/08/2024'],
        ];
    }

    /**
     * Define the headings for the export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Section',
            'Number',
            'Task',
            'Bobot',
            'ES (day th)',
            'Start Date',
            'Duration',
            'EF (day th)',
            'End Date',
        ];
    }

    /**
     * Register events.
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->mergeCells('A2:A3');
                $sheet->mergeCells('A4:A4');

                $styleArray = [
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                ];

                $event->sheet->getStyle('A2:A4')->applyFromArray($styleArray);

                $sheet->getStyle('G2:G1000')
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);

                $sheet->getStyle('J2:J1000')
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
            },
        ];
    }
}
