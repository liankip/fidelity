<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PurchaseOrderCreated extends Notification
{
    use Queueable;
    private $podata;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($podata)
    {
        $this->podata = $podata;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // return ['mail'];
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
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $itemsHtml = '';
        $maxItems = 3;
        $numItems = count($this->podata["podetail"]);

        foreach ($this->podata["podetail"] as $key => $value) {
            if ($key >= $maxItems) {
                break;
            }
            $itemsHtml .= "<li>".$value->item->name."</li>";
        }

        if ($numItems > $maxItems) {
            $itemsHtml .= "<li>...</li>";
        }

        return [
            'name' => 'New Purchase Order in Project '.$this->podata["project_name"],
            'body' => 'New Purchase Order created by ' .$this->podata["created_by"] .  ' at <b>' . $this->podata['supplier_name'] . "</b> with the following list of items :
                    <ul>
                        {$itemsHtml}
                    </ul>",
            'text' => 'Check out the offer',
            'url' => url('/po_details/'.$this->podata["po_detail"]),
        ];
    }
}
