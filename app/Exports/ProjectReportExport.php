<?php

namespace App\Exports;

use App\Models\Project;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class ProjectReportExport implements FromCollection, WithHeadings, WithEvents
{

    protected $projectId;

    public function __construct($projectId)
    {
        $this->projectId = $projectId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $projects = Project::with(['purchase_orders', 'boqs' => function ($query) {
            $query->select('project_id', DB::raw('SUM(price_estimation) as total_price_estimation'))
                ->groupBy('project_id');
        }])
        ->where('id', $this->projectId)
        ->where('status', 'Finished')
        ->whereNull('deleted_at')
        ->groupBy('id', 'name', 'status')
        ->get();

        $collections = new Collection();

        foreach ($projects as $project) {
            $approvals = $this->returnApprove($project);
            $collections->push([
                'id' => $project->id,
                'name' => $project->name,
                'budget' => rupiah_format($project->value),
                'BOQ_estimation' => rupiah_format(optional($project->boqs)->first()->total_price_estimation ?? 0),
                'qty_request' => $approvals['notApproved']['count'] > 0 ? $approvals['notApproved']['count'] : '0',
                'qty_dibelanjakan' => $approvals['approved']['count'] > 0 ? $approvals['approved']['count'] : '0',
                'items_request' => $approvals['notApproved']['items'],
                'items_dibelanjakan' => $approvals['approved']['items'],
            ]);
        }
        return $collections;

    }


    public function headings(): array
    {
        return [
            'Id Project',
            'Nama Project',
            'Budget Project',
            'BOQ Estimation',
            'Qty Item Request',
            'Qty Item Dibelanjakan',
            'Items Request',
            'Items Dibelanjakan',
        ];
    }

    public function returnApprove($project) {
        $approvedItems = [];
        $notApprovedItems = [];
        $approved = 0;
        $notApproved = 0;
    
        foreach ($project->purchase_orders as $po) {
            foreach ($po->podetail as $podetail) {
                $itemName = $podetail->item->name;
                $itemQty = $podetail->qty;
                $ponumber = $po->po_no;
    
                if ($po->status === 'Approved') {
                    $approvedItems[$itemName] = $approvedItems[$itemName] ?? [
                        'name' => $itemName,
                        'qty' => 0,
                        'po-numbers' => [],
                    ];
                    $approvedItems[$itemName]['qty'] += $itemQty;
    
                    if (!empty($ponumber)) {
                        $approvedItems[$itemName]['po-numbers'][] = $ponumber;
                    }
                } else {
                    $notApprovedItems[$itemName] = $notApprovedItems[$itemName] ?? 0;
                    $notApprovedItems[$itemName] += $itemQty; 
                }
            }
        }
    
        $formattedApprovedItems = array_map(function ($item) {
            $poNumbers = implode(', ', $item['po-numbers']);
            return $item['name'] . ', Qty: ' . $item['qty'] . (!empty($poNumbers) ? ', PO-NO: (' . $poNumbers . ')' : '');
        }, $approvedItems);
    
        return [
            'approved' => [
                'count' => array_sum(array_column($approvedItems, 'qty')),
                'items' => implode("\n", $formattedApprovedItems),
            ],
            'notApproved' => [
                'count' => array_sum($notApprovedItems),
                'items' => implode(', ', array_keys($notApprovedItems)),
            ],
        ];
    }
    
    
    

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $columnWidths = [
                    'A' => 15,
                    'B' => 15,
                    'C' => 30,
                    'D' => 20,
                    'E' => 20,
                    'F' => 20,
                    'G' => 20,
                    'H' => 80,
                ];
            
                foreach ($columnWidths as $column => $width) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setWidth($width);
                }
            
                $columnRange = 'A:H'; 
                $event->sheet->getDelegate()->getStyle($columnRange)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('H')->getAlignment()->setWrapText(true);
            },
            
        ];
    }

    
}
