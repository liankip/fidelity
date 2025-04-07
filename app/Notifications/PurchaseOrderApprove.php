<?php

namespace App\Notifications;

use App\Mail\SendEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PurchaseOrderApprove extends Notification
{
    use Queueable;
    private $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        return (new SendEmail($this->data["po"],"IT"));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'name' => "Purchase Order Approved",
            'body' => 'Purchase Order with po number <b>' . $this->data["po_no"] . '</b> has been approved by ' . $this->data["action_by"],
            'text' => 'Check out the offer',
            'url' => url('/po_details/' . $this->data["po_detail"]),
        ];
    }
}
