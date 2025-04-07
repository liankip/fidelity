<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PurchaseOrderTerimaBarangExport implements FromView, WithColumnFormatting, ShouldAutoSize, WithStyles
{
    private $purchase_orders;

    public function __construct($purchase_orders)
    {
        $this->purchase_orders = $purchase_orders;
    }

    public function view(): View
    {
        return view('excel.purchase-order-terima-barang', [
            'purchase_orders' => $this->purchase_orders,
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet
            ->getStyle('B:G')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
    }
}
