<?php

namespace App\Traits;

use App\Models\CompanyDetail;

use App\Models\PurchaseOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\PurchaseOrderUtils;
use Illuminate\Support\Facades\File;

trait PurchaseOrderDocuments
{
    public function generateDocument($po_id)
    {
        $po_data = PurchaseOrder::with('podetail', 'payments', 'submition', 'invoices')->where('id', $po_id)->first();
        $po_detail = $po_data->podetail;
        $total_amount = $po_data->podetail->sum('amount');

        $taxi = $po_data->podetail->first();
        if ($taxi->tax_status != 2) {
            $total_tax = 11;
        } else {
            $total_tax = 0;
        }
        $get_prtype = $po_data->pr;
        $our_company = CompanyDetail::first();
        $getproject_name = $get_prtype->project->project_name;

        if ($po_data->approved_at) {
            $newDate = date_format(date_create($po_data->approved_at), 'F d, Y');
        } elseif ($po_data->date_approved_2) {
            $newDate = date_format(date_create($po_data->date_approved_2), 'F d, Y');
        } elseif ($po_data->date_approved) {
            $newDate = date_format(date_create($po_data->date_approved), 'F d, Y');
        } else {
            $newDate = date_format(date_create($po_data->cretated_at), 'F d, Y');
        }

        $pdf = PDF::loadView('pdf-views.po-compiled-document', compact([
            'po_data',
            'po_detail',
            'total_amount',
            'total_tax',
            'getproject_name',
            'newDate',
            'get_prtype',
            'our_company'
        ]));

        $fileName = "Document Purchase Order - " . PurchaseOrderUtils::getPoNumber($po_data->po_no) . ".pdf";
        $path = storage_path('app/public/documents/');
        $filePath = $path . $fileName;

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        $pdf->save($filePath);

        $po_data->completeDocument()->create([
            'file_name' => $fileName,
            'file_path' => "documents/$fileName"
        ]);
    }
}
