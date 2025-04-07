<?php

namespace App\Exports;

use App\Models\InventoryDetail;
use App\Models\Project;
use App\Models\PurchaseRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;

class InventoryOutExport implements FromCollection, WithHeadings, WithStyles, WithMapping, WithStrictNullComparison, WithCustomStartCell, WithEvents
{
    public $projectId;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($paramId)
    {
        $this->projectId = $paramId;
    }
    public function collection()
    {
        $items =  InventoryDetail::where('inventory_details.project_id', $this->projectId)
            ->join('inventories', 'inventory_details.inventory_id', '=', 'inventories.id')
            ->join('items', 'inventories.item_id', '=', 'items.id')
            ->leftJoin('purchase_requests', 'purchase_requests.project_id', '=', 'inventory_details.project_id')
            ->orderBy('items.name', 'asc')
            ->select('inventory_details.*', 'items.name as item_name')
            ->with(['inventory.item', 'inventory_outs'])
            ->distinct()
            ->get();

        $mergedItems = [];

        $purchaseRequests = PurchaseRequest::where('project_id', $this->projectId)->where('status', 'Processed')->orWhere('status', 'Partially')
            ->with('po', 'po.podetail')
            ->get();

        // Preprocess purchaseRequests to create indexed arrays for quick lookups
        $poIndex = [];
        $podetailIndex = [];

        foreach ($purchaseRequests as $purchaseRequest) {
            foreach ($purchaseRequest->po as $po) {
                if (($po->status === 'Approved' || strtolower($po->status) === 'need to pay' || strtolower($po->status) === 'paid') && ($po->status_barang === 'Arrived' || $po->status_barang === 'Partially Arrived') && $po->project_id === intval($this->projectId)) {
                    $poIndex[$po->id] = $po;
                    foreach ($po->podetail as $podetail) {
                        $podetailIndex[$podetail->item_id][] = [
                            'po' => $po,
                            'podetail' => $podetail,
                            'partof' => $purchaseRequest->partof,
                        ];
                    }
                }
            }
        }

        // Process the items and merge them with purchaseRequest details
        foreach ($items as $item) {
            $total = 0;
            $item_id = $item->inventory->item->id;

            if (isset($podetailIndex[$item_id])) {
                foreach ($podetailIndex[$item_id] as $detail) {
                    $podetail = $detail['podetail'];
                    $po = $detail['po'];
                    $total += $podetail->qty;

                    $existingItemIndex = null;

                    // Check if the item with the same inventory_id already exists in mergedItems
                    foreach ($mergedItems as $index => $mergedItem) {
                        if ($mergedItem->inventory_id === $item->inventory_id) {
                            $existingItemIndex = $index;
                            break;
                        }
                    }

                    if ($existingItemIndex !== null) {
                        // Override the existing item
                        $mergedItems[$existingItemIndex]->total = $total;
                        $mergedItems[$existingItemIndex]->podetailid = $podetail->id;
                        $mergedItems[$existingItemIndex]->poid = $po->id;
                        $mergedItems[$existingItemIndex]->earlystock = $total;
                        $mergedItems[$existingItemIndex]->partof = $detail['partof'];
                    } else {
                        // Create a new item
                        $itemClone = clone $item;
                        $itemClone->podetailid = $podetail->id;
                        $itemClone->poid = $po->id;
                        $itemClone->earlystock = $total;
                        $itemClone->partof = $detail['partof'];
                        $itemClone->total = $total;

                        $mergedItems[] = $itemClone;
                    }
                }
            }
        }

        return collect($mergedItems);
    }

    public function map($item): array
    {
        $filteredInventoryOuts = $item->inventory_outs->where('partof', $item->partof);
        static $no = 1;

        return [
            $no++,            // No
            $item->inventory->item->name,               // Nama Item
            $item->partof ?? '',            // Bagian
            $item->earlystock ?? 0,         // Stok Awal
            $item->stock ?? '0', // Stok Keluar
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Item',
            'Bagian',
            'Stok',
            'Stok Lapangan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A4:E4')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'e7e2fd',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'c8c8c8'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        // $sheet->getColumnDimension('F')->setWidth(15);
        // $sheet->getColumnDimension('G')->setWidth(15);

        return [
            4 => ['font' => ['bold' => true]], 
        ];
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function registerEvents(): array
    {
        $projectName = Project::find($this->projectId)->name;

        return [
            AfterSheet::class => function (AfterSheet $event) use ($projectName) {
                $event->sheet->setCellValue('A1', $projectName);
                $event->sheet->mergeCells('A1:E1'); 
                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ]
                ]);
            },
        ];
    }
}
