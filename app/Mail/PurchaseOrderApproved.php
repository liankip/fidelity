<?php

namespace App\Mail;

use App\Helpers\PurchaseOrderUtils;
use App\Models\CompanyDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PurchaseOrderApproved extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $po;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($po)
    {
        $this->po = $po;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $fileName = "Purchase Order - " . PurchaseOrderUtils::getPoNumber($this->po->po_no) . ".pdf";

        $total_amount = $this->po->podetail->sum('amount');
        $taxi = $this->po->podetail->first();
        if ($taxi->tax_status != 2) {
            $total_tax = 11;
        } else {
            $total_tax = 0;
        }

        $get_prtype = $this->po->pr ?? null;
        $our_company = CompanyDetail::first();

        if ($this->po->approved_at) {
            $newDate = date_format(date_create($this->po->approved_at), 'F d, Y');
        } elseif ($this->po->date_approved_2) {
            $newDate = date_format(date_create($this->po->date_approved_2), 'F d, Y');
        } elseif ($this->po->date_approved) {
            $newDate = date_format(date_create($this->po->date_approved), 'F d, Y');
        } else {
            $newDate = date_format(date_create($this->po->cretated_at), 'F d, Y');
        }

        $data = [
            'po_data' => $this->po,
            'po_detail' => $this->po->podetail,
            'total_amount' => $total_amount,
            'total_tax' => $total_tax,
            'getproject_name' => $this->po->project->name ?? '-',
            'newDate' => $newDate,
            'get_prtype' => $get_prtype,
            'our_company' => $our_company
        ];

        $pdf = Pdf::loadView('pdf-views.print-po', $data);

        $subject = PurchaseOrderUtils::getEmailSubject($this->po->po_no);

        return $this->from('notification@dcs.group', env('COMPANY') . ' Notification')->subject($subject)->attachData($pdf->output(), $fileName, [
            'mime' => 'application/pdf',
        ])->view('emails.purchase-order.approved');
    }
}
