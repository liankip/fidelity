<?php

namespace App\Exports;

use App\Models\PurchaseOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ProjectHistoryExport implements FromCollection, WithHeadings, WithStyles
{
    public $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function collection()
    {
        return PurchaseOrder::whereHas("pr", function ($query) {
            $query->where("project_id", $this->project_id);
        })->where(function ($query) {
            $query->where('purchase_orders.status', 'approved')
                ->orWhere('purchase_orders.status', 'Partially Paid')
                ->orWhere('purchase_orders.status', 'Paid')
                ->orWhere('purchase_orders.status', 'completed');
        })->leftJoin("purchase_requests", "purchase_requests.pr_no", "=", "purchase_orders.pr_no")
            ->leftJoin("projects", "purchase_requests.project_id", "=", "projects.id")
            ->leftJoin('purchase_order_details', 'purchase_orders.id', '=', 'purchase_order_details.purchase_order_id')
            ->leftJoin("purchase_request_details", "purchase_order_details.purchase_request_detail_id", "=", "purchase_request_details.id")
            ->select(
                'purchase_orders.po_no',
                "purchase_requests.pr_no",
                "projects.name",
                "purchase_orders.date_approved",
                "purchase_orders.status",
                "purchase_orders.status_barang",
                'purchase_request_details.item_name',
                'purchase_order_details.qty',
                "purchase_order_details.price",
                \DB::raw('purchase_order_details.price * purchase_order_details.qty as total_price_per_item'),
                "purchase_order_details.tax",
                \DB::raw('
                    CASE 
                        WHEN purchase_order_details.tax = 0 THEN 0 
                        ELSE purchase_orders.total_amount - (purchase_orders.total_amount / 1.11) 
                    END as price_before_tax
                '),
                "purchase_orders.tarif_ds",
                "purchase_orders.total_amount"
            )
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nomor PO',
            'Nomor PR',
            'Project',
            'Date',
            'Status',
            'Status Barang',
            'Description',
            'QTY',
            'Price',
            'Total Price Per Item',
            'Tax',
            'Tax Value',
            'Ongkir',
            'Total',
        ];
    }

    public function styles($sheet)
    {
        $sheet->getStyle('A1:N1')->applyFromArray([
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
