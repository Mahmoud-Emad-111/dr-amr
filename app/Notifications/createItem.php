<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class createItem extends Notification
{
    use Queueable;

    private $item;
    private $message;
    /**
     * Create a new notification instance.
     */
    public function __construct($item,$message)
    {
        //
        $this->item=$item;
        $this->message=$message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message'=>$this->message,
            'ID'=>$this->item->random_id,
            'Title'=>$this->item->title==null ? $this->item->name : $this->item->title,
        ];
    }
}
