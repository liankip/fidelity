<?php

namespace App\Exports;

use App\Models\OfficeExpensePurchase;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class OfficeExpensePurchaseExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents
{
    protected $purchase;
    protected $start_date;
    protected $end_date;

    public function __construct($purchase = null, $start_date = null, $end_date = null)
    {
        $this->purchase = $purchase;
        $this->start_date = Carbon::parse($start_date)->format('Y-m-d');
        $this->end_date = Carbon::parse($end_date)->format('Y-m-d');
    }

    public function array(): array
    {
        $data = OfficeExpensePurchase::with('officeExpenseItem')
            ->selectRaw(
                'office_expense_purchases.id,
                office_expense_items.purchase_date,
                office_expense.office,
                office_expense_purchases.purchase_name,
                COALESCE(SUM(office_expense_items.total_expense), 0) as total_expense,
                office_expense_items.notes,
                office_expense_items.status',
            )
            ->leftJoin('office_expense', 'office_expense_purchases.office_expense_id', '=', 'office_expense.id')
            ->leftJoin('office_expense_items', 'office_expense_purchases.id', '=', 'office_expense_items.office_expense_purchase_id')
            ->where('office_expense_purchases.id', $this->purchase)
            ->whereHas('officeExpenseItem', function ($query) {
                $query->when(!empty($this->start_date) && !empty($this->end_date), function ($q) {
                    $q->whereBetween('purchase_date', [$this->start_date, $this->end_date]);
                });
            })
            ->groupBy('office_expense_purchases.id', 'office_expense_items.purchase_date', 'office_expense.office', 'office_expense_purchases.purchase_name', 'office_expense_items.notes')
            ->get();

        $exportData = [];
        $counter = 1;

        foreach ($data as $item) {
            $exportData[] = [
                'no' => $counter++,
                'purchase_date' => $item->purchase_date,
                'office' => $item->office,
                'purchase_name' => $item->purchase_name,
                'total_expense' => $item->total_expense,
                'notes' => $item->notes,
                'status' => $item->status
            ];
        }

        $totalExpense = array_sum(array_map(fn($row) => floatval(str_replace(',', '.', $row['total_expense'])), $exportData));

        $exportData[] = ['', '', '', 'Total', rupiah_format($totalExpense), ''];

        return $exportData;
    }

    public function headings(): array
    {
        return ['No', 'Purchase Date', 'Office', 'Purchase Name', 'Total Expense', 'Notes'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $cols = array_keys($sheet->getColumnDimensions());

                $event->sheet->getStyle('E2:E100')->getNumberFormat()->setFormatCode('#,##0.00');

                foreach ($cols as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
