<?php

namespace App\Notifications;

use App\Mail\SendEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VoucherApprove extends Notification
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
            'name' => "Voucher Approved",
            'body' => 'Voucher with voucher number <b>' . $this->data["voucher_no"] . '</b> has been approved by ' . $this->data["action_by"],
            'text' => 'Check out the offer',
            'url' => url('/voucher_aprv_waitinglists/'),
        ];
    }
}
