<?php

namespace App\Mail;

use App\Models\Payment;
use App\Models\PurchaseOrder;
use App\Models\Voucher;
use App\Models\VoucherDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class PaymentVoucher extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $publicId;
    // public $voucherData;
    public $voucherDetail;
    public $taxStatus;
    public $poData;
    public $terminStatus;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($paramId, $statusParam)
    {
        $this->publicId = $paramId;
        // $this->voucherData = Voucher::where('id', $this->voucherId)->first();
        $this->voucherDetail = VoucherDetail::with('voucherPayment')->where('purchase_order_id', $paramId)->latest('created_at')->first();

        switch ($statusParam) {
            case 'No':
                $this->taxStatus = 'No';
                break;
            case 'Yes';
                $this->taxStatus = 'Yes';
                break;
            default:
                $this->taxStatus = 'None';
                break;
        }

        // $dataPo = PurchaseOrder::where('id', $paramId)->first();
        // $dataPayment = Payment::where('po_id', $dataPo->id)->get();
        
        // if(($dataPo->term_of_payment === 'Termin 2' && count($dataPayment) < 2) || ($dataPo->term_of_payment === 'Termin 3' && count($dataPayment) < 3)){
        //     $this->terminStatus = 'Belum Lunas';
        // } else {
        //     $this->terminStatus = 'Lunas';
        // }

        $this->poData = VoucherDetail::with([
            'purchase_order',
            'purchase_order.do',
            'purchase_order.invoices',
            'purchase_order.submition',
        ])->where('purchase_order_id', $this->publicId)->get()->groupBy('purchase_order_id')->toArray();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Notifikasi pembayaran PO dengan No. ' . $this->voucherDetail->purchase_order->po_no . ' telah selesai';

        $pdf = Pdf::loadView('pdf-views.payment-voucher', [
            'voucherData' => $this->voucherDetail,
            'poData' => $this->poData,
            'taxStatus' => $this->taxStatus,
            // 'terminStatus' => $this->terminStatus
        ]);

        $fileName = str_replace('/', '-', $this->voucherDetail->purchase_order->po_no) . '.pdf';
        $filePath = 'payment_voucher/' . $fileName;

        // Store the PDF
        Storage::disk('public')->put($filePath, $pdf->output());

        return $this->from('notification@dcs.group', env('COMPANY') . ' Notification')->subject($subject)
            ->view('emails.vouchers.payment-voucher-notification')
            ->attachData($pdf->output(), $this->voucherDetail->purchase_order->po_no . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
