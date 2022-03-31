<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Illuminate\Support\HtmlString;

class OrderConfirmation extends Notification
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
                    ->subject('Confirmation of your Sol Cleanse order #'.$this->order->id)
					->greeting('Hi '.$notifiable->first_name)
                    ->line('We are delighted to confirm your order with us.')
                    ->line(new HtmlString($this->order->orderDetailsEmail()))
                    ->line(new HtmlString($this->order->ordertotalsEmail()))
                    ->line(new HtmlString('We\'ve created an account for you and saved your card for future use. To login again, first <a href="'.url('/forgot-password').'">set your password here</a>'))
                    ->line('We look forward to fulfilling your order soon and supporting you on your wellness journey. ')
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
