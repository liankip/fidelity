<?php

namespace App\Exports;

use App\Models\SupplierItemPrice;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PricesExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    /**
     * @return Collection
     */
    public function collection()
    {
        $prices = SupplierItemPrice::with(['supplier', 'item', 'unit'])
            ->orderBy('id', 'desc')
            ->get();

        return $prices->map(function ($price, $index) {
            return array_merge(['no' => $index + 1], $price->toArray());
        });
    }

    /**
     * Map the data for each row.
     */
    public function map($price): array
    {
        return [
            $price['no'],
            $price['supplier']['name'] ?? 'N/A',
            $price['item']['name'] ?? 'N/A',
            $price['unit']['name'] ?? 'N/A',
            'Rp. ' . number_format($price['price'], 0, ',', '.'),
            $price['supplier']['term_of_payment'] ?? 'N/A',
            $this->getTaxStatus($price['tax_status']),
            $price['old_idr_by_usd'] > 0 ? $price['old_idr_by_usd'] : 'None',
        ];
    }

    /**
     * Define the column headings.
     */
    public function headings(): array
    {
        return ['No', 'Supplier Name', 'Item Name', 'Unit Name', 'Price', 'Term of Payment', 'Tax Status', 'Old IDR by USD'];
    }

    /**
     * Format the tax status.
     */
    private function getTaxStatus($status)
    {
        return match ($status) {
            0 => 'Exclude',
            1 => 'Include',
            2 => 'Non PPN',
            default => 'Unknown',
        };
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                foreach (range('A', 'H') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                $sheet->getStyle('E2:E7')->getNumberFormat()->setFormatCode('"Rp." #,##0');
            },
        ];
    }
}
