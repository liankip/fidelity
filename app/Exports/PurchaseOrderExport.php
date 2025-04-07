<?php

namespace App\Exports;

use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class PurchaseOrderExport implements FromCollection, WithHeadings, WithEvents
{
    private $date_from;
    private $date_to;
    private $supplier;

    public function __construct(string $date_from, string $date_to, int $supplier = null)
    {
        $this->date_from = $date_from;
        $this->date_to = $date_to;
        $this->supplier = $supplier;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = DB::table("purchase_orders")
            ->leftJoin("suppliers", function ($join) {
                $join->on("suppliers.id", "=", "purchase_orders.supplier_id");
            })
            ->leftJoin("purchase_order_details", function ($join) {
                $join->on("purchase_order_details.purchase_order_id", "=", "purchase_orders.id");
            })
            ->leftJoin("purchase_request_details", function ($join) {
                $join->on("purchase_request_details.id", "=", "purchase_order_details.purchase_request_detail_id");
            })
            ->leftJoin("projects", function ($join) {
                $join->on("projects.id", "=", "purchase_orders.project_id");
            })
            ->select(DB::raw("DATE_FORMAT(purchase_orders.date_approved , '%d/%m/%Y') as date_approved"), "purchase_orders.po_no as po_number", "purchase_orders.term_of_payment" ,"suppliers.name as supplier", "purchase_request_details.item_name as keterangan", "purchase_order_details.qty as Qty", "purchase_request_details.unit", "purchase_order_details.price as harga", DB::raw("(purchase_order_details.price * purchase_order_details.qty) as dpp"), DB::raw("case purchase_order_details.tax_status when 0 then (purchase_order_details.price * purchase_order_details.qty * 0.11) when 1 then (purchase_order_details.price * purchase_order_details.qty * 0.11) when 2 then '-' else '-' end as PPN"), DB::raw("case purchase_order_details.tax_status when 0 then (purchase_order_details.amount + (purchase_order_details.amount * 0.11)) when 1 then (purchase_order_details.amount + (purchase_order_details.amount * 0.11)) when 2 then purchase_order_details.amount else purchase_order_details.amount end as Total"), "projects.name as project", DB::raw("case purchase_order_details.tax_status when 0 then 'PPN' when 1 then 'PPN' when 2 then '-' else '-' end as ppn_status"), "purchase_orders.status as status")
            ->whereBetween('purchase_orders.created_at', [$this->date_from . ' 00:00:00', $this->date_to . ' 23:59:59'])
            ->when($this->supplier !== null, function ($query, $supplier) {
                $query->where('supplier_id', $supplier);
            })
            // ->where("purchase_orders.created_at", "<=", $this->date_to . ' 23:59:59')
            // ->where(function ($query) {
            //     $query->where("purchase_orders.status", "Approved")
            //         ->orWhere("purchase_orders.status", "Completed")
            //         ->orWhere("purchase_orders.status", "Cancel");
            // })
            ->get();

        $poData = PurchaseOrder::where('created_at', '>=', $this->date_from . ' 00:00:00')->get();
        $formattedData = $data->map(function ($item, $key) use ($poData) {
            $poRecord = $poData->where('po_no', $item->po_number)->first();

            if ($item->status === 'Approved') {
                $poTerm = strtolower($poRecord->term_of_payment);
                $hasInvoice = $poRecord->hasInvoice();
                $hasDO = count($poRecord->do) > 0;
                $hasSubmition = $poRecord->hasSubmition();
                $hasPayments = count($poRecord->payments) > 0;

                if ($poTerm === 'cash' && $hasInvoice && !$hasPayments) {
                    $item->status = 'Ready to pay';
                }

                if ($poTerm !== 'cash' && $hasInvoice && $hasDO && $hasSubmition && !$hasPayments) {
                    $item->status = 'Ready to pay';
                }
            }

            $item->Harga_Satuan = number_format($item->harga, 2);
            $item->DPP = number_format($item->dpp, 2);
            $item->PPN = is_numeric($item->PPN) ? number_format($item->PPN, 2) : '-';
            $item->Total = number_format($item->Total, 2);

            $row = [
                'No' => $key + 1,
                'Tanggal' => $item->date_approved,
                'Nomor PO' => $item->po_number,
                'Supplier' => $item->supplier,
                'Term of Payment' => $item->term_of_payment,
                'Keterangan' => $item->keterangan,
                'Qty' => $item->Qty,
                'Satuan' => $item->unit,
                'Harga Satuan' => $item->Harga_Satuan,
                'DPP' => $item->DPP,
                'PPN' => $item->PPN,
                'Jumlah' => $item->Total,
                'Project' => $item->project,
                'PPN Status' => $item->ppn_status,
                'PO Status' => $item->status
            ];

            return (object)$row;
        });

        return $formattedData;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Nomor PO',
            'Supplier',
            'Term of Payment',
            'Keterangan',
            'Qty',
            'Satuan',
            'Harga Satuan',
            'DPP',
            'PPN',
            'Jumlah',
            'Project',
            'PPN Status',
            'PO Status'
        ];
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {

                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(19);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(22);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(18);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(15);
            },
        ];
    }
}
