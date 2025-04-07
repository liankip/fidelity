<?php

namespace App\Mail;

use App\Models\InventoryDetail;
use App\Models\InventoryOut;
use App\Models\PurchaseRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InventoryOutMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $todayDate;
    public $todayData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->todayDate = Carbon::today();
        $dataOut = InventoryOut::whereDate('created_at', '=', $this->todayDate)->where('out', '!=', null)->where('out', '>', 0)->orderby('project_id')->get()->groupBy('project_id');
        $inventoryDetails = $dataOut->map(function ($items, $projectId) {
            return $items->map(function ($item) {
                $todayOut = $item->out;
                $inventoryDetail = InventoryDetail::find($item->inventory_detail_id);
                $remainingStock = $inventoryDetail ? $inventoryDetail->stock : 0;
                $totalStock = $inventoryDetail->inventory_outs->sum('out') + $remainingStock;
                $notes = $inventoryDetail->note ?? '-';
                $itemName = $inventoryDetail->inventory->item->name;
        
                $detail = new \stdClass();
                $detail->inventoryDetail = $inventoryDetail;
                $detail->itemName = $itemName;
                $detail->totalstock = $totalStock;
                $detail->todayOut = $todayOut;
                $detail->remainingStock = $remainingStock;
                $detail->note = $notes;
        
                return $detail;
            });
        });

        // Convert the result to a Collection
        $this->todayData = collect($inventoryDetails);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        Carbon::setLocale('id');
        try {
            $subject = 'Notifikasi Barang Keluar Tanggal ' . Carbon::parse($this->todayDate)->translatedFormat('j F Y');

            $pdf = Pdf::loadView('pdf-views.inventory-out', [
                'date' => $this->todayDate,
                'inventoryData' => $this->todayData
            ]);

            $fileName = 'Barang Keluar ' . Carbon::parse($this->todayDate)->translatedFormat('j F Y') . '.pdf';
            $filePath = 'inventory_out/' . $fileName;

            // Store the PDF
            Storage::disk('public')->put($filePath, $pdf->output());

            return $this->from('notification@dcs.group', env('COMPANY') . ' Notification')->subject($subject)
                ->view('emails.inventory.inventory-out')
                ->attachData($pdf->output(), $fileName, [
                    'mime' => 'application/pdf',
                ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
