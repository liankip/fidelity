<?php

namespace App\Exports;

use App\Models\OfficeExpense;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class OfficeExpenseExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents
{
    protected $office;
    protected $start_date;
    protected $end_date;
    private $mergeRanges = [];

    public function __construct($office = null, $start_date = null, $end_date = null)
    {
        $this->office = $office;
        $this->start_date = Carbon::parse($start_date)->format('Y-m-d');
        $this->end_date = Carbon::parse($end_date)->format('Y-m-d');
    }

    public function headings(): array
    {
        $officeName = OfficeExpense::where('id', $this->office)->first()->office ?? '';

        return [['Kantor: ' . ($officeName ?? '')], [], ['No', 'Purchase Name', 'Tanggal', 'Expense', 'Note']];
    }

    public function array(): array
    {
        $data = OfficeExpense::with(['officeExpensePurchase.officeExpenseItem'])
            ->where('id', $this->office)
            ->whereHas('officeExpensePurchase.officeExpenseItem', function ($query) {
                $query->whereBetween('purchase_date', [$this->start_date, $this->end_date]);
            })
            ->get();

        $exportData = [];
        $counter = 1;
        $rowIndex = 4;

        foreach ($data as $expense) {
            foreach ($expense->officeExpensePurchase as $purchase) {
                $firstRow = $rowIndex;
                $hasMultipleRows = false;

                foreach ($purchase->officeExpenseItem as $item) {
                    $exportData[] = [
                        'no' => $counter++,
                        'purchase_name' => $purchase->purchase_name,
                        'tanggal' => $item->purchase_date,
                        'expense' => (float) $item->total_expense,
                        'notes' => $item->notes,
                        'status' => $item->status
                    ];
                    $rowIndex++;
                    $hasMultipleRows = true;
                }

                if ($hasMultipleRows && $firstRow < $rowIndex - 1) {
                    $this->mergeRanges[] = "B{$firstRow}:B" . ($rowIndex - 1);
                }
            }
        }

        $totalExpense = array_sum(array_column($exportData, 'expense'));
        $exportData[] = ['', '', 'Total', rupiah_format($totalExpense), ''];

        return $exportData;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $cols = array_keys($sheet->getColumnDimensions());

                foreach ($cols as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                $sheet->mergeCells('A1:E1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center')->setVertical('center');

                $event->sheet->getDelegate()->getStyle('B4:B100')->getAlignment()->setVertical('center');

                $event->sheet->getStyle('D4:D100')->getNumberFormat()->setFormatCode('#,##0.00');

                foreach ($this->mergeRanges as $range) {
                    $event->sheet->mergeCells($range);
                }
            },
        ];
    }
}
