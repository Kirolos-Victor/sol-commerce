<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class SubscriptionRenewed extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($subscription)
    {
        $this->subscription = $subscription;
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
                    ->subject('Your Sol Cleanse Subscription '.$this->subscription->id.' renewal is confirmed')
					->greeting('Hi '.$notifiable->first_name)
                    ->line('We hope you are enjoying nourishing your body with a Sol Cleanse Subscription.')
                    ->line('This is a friendly confirmation that your '.$this->subscription->variant->product->title.' subscription has been renewed.')
                    ->line('Starting '.$this->subscription->created_at->format('d/m/Y').', your subscription will automatically renew for $'.$this->subscription->price.'. This is a rolling subscription that will continue every '.$this->subscription->frequency.' weeks.')
                    ->line(new HtmlString('To update your payment or delivery details, kindly visit your <a href="'.url('/account').'">account</a>.'))
                    ->line('We look forward to fulfilling your subscription soon and supporting you on your wellness journey.')
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
