<?php

namespace App\Mail;

use App\Models\MinutesOfMeeting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class MinuteOfMeetingApproval extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $meeting;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($meeting)
    {
        $this->meeting = $meeting;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'MoM Project: ' . $this->meeting->project->name . ' "' . $this->meeting->meeting_title . '" ' . Carbon::parse($this->meeting->meeting_date)->format('d M Y'),
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
            view: 'emails.minutes_of_meeting_approval',
            with: [
                'meeting' => $this->meeting,
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
        $fileUploads = json_decode($this->meeting->upload_file, true);

//        $attachments = [];

        foreach ($fileUploads as $file) {
            $fileContent = Http::get($file['url'])->body();

            $attachments[] = Attachment::fromData(
                fn() => $fileContent,
                $file['name']
            )->withMime('application/octet-stream');
        }

        $pdf = Pdf::loadView('pdf-views.minutes_of_meeting_approval', [
            'meeting' => $this->meeting
        ]);

        $fileName = 'minutes_of_meeting_' . Carbon::now()->format('Y_m_d') . '.pdf';

        $attachments[] = Attachment::fromData(fn() => $pdf->output(), $fileName)
            ->withMime('application/pdf');

        return $attachments;
    }
}
