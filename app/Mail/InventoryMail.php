<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use FontLib\Table\Type\name;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InventoryMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    

    public $inventoryData;
    public function __construct($paramData)
    {
        $this->inventoryData = collect($paramData)->groupBy('project.name');
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        Carbon::setLocale('id');
        return new Envelope(
            subject: 'Notifikasi Update Inventory Tanggal ' . Carbon::parse(Carbon::now())->translatedFormat('j F Y'),
            from: 'notification@dcs.group',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.inventory.inventory-notification',
            with: [
                'todayDate' => Carbon::now(),
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        $pdf = Pdf::loadView('pdf-views.inventory', [
            'inventoryData' => $this->inventoryData
        ]);
    
        $fileName = 'Update Inventory ' . Carbon::parse(Carbon::now())->translatedFormat('j F Y') . '.pdf';
    
        return [
            Attachment::fromData(fn() => $pdf->output(), $fileName)
                ->withMime('application/pdf'),
        ];
    }
}
