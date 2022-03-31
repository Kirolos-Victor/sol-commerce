<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class OrderCancelled extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
                    ->subject('Sol Cleanse order '.$this->order->id.' has been cancelled')
					->greeting('Hi '.$notifiable->first_name)
                    ->line('This is a notification to confirm that your order '.$this->order->id.' has now been cancelled.')
                    ->line(new HtmlString($this->order->orderDetailsEmail()))
                    ->line(new HtmlString($this->order->ordertotalsEmail()))
                    ->line(new HtmlString('If there is any way we can help you further, please contact our friendly <a href="'.url('/contact').'">customer support team</a>.'))
                    ->line('With Gratitude ~')
					->salutation('Sol Cleanse');
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
            //
        ];
    }
}
