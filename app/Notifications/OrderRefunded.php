<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class OrderRefunded extends Notification
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
                    ->subject('Your Sol Cleanse order '.$this->order->id.' has been refunded')
					->greeting('Hi '.$notifiable->first_name)
                    ->line('Your order on Sol Cleanse has been refunded')
                    ->line('There are details below for your reference:')
                    ->line(new HtmlString($this->order->orderDetailsEmail()))
                    ->line(new HtmlString($this->order->ordertotalsEmail()))
                    ->line('Refunds may take 3-5 business days to appear in your account. Thank you for your cooperation.')
                    ->line(new HtmlString('If there is any way we can help you further, please contact Sol Cleanse <a href="'.url('/contact').'">customer care</a>.'))
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
