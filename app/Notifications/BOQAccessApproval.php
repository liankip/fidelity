<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BOQAccessApproval extends Notification
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
        if ($notifiable == auth()->user()) {
            return [
                'name' => 'your request to ' . $this->data["action"]  . ' BOQ for Project ' . $this->data["project_name"] . ' has been sent',
                'body' => 'Your request to ' . $this->data["action"]  . ' BOQ for Project ' . $this->data["project_name"] . ' has been sent to manager, please wait for approval',
                'text' => '',
                'url' => $this->data["url"],
            ];
        }

        return [
            'name' => '<span style="font-weight: bold;">' . $this->data["editor"] . '</span> request to ' . $this->data["action"]  . ' BOQ for Project ' . $this->data["project_name"],
            'body' => 'Please approve this request to ' . $this->data["action"]  . ' BOQ for Project ' . $this->data["project_name"],
            'text' => '',
            'url' => $this->data["url"],
        ];
    }
}
