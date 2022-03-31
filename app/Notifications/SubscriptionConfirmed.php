<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionConfirmed extends Notification
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
                    ->subject('Confirmation of your Sol Cleanse Subscription '.$this->subscription->id.'')
					->greeting('Hi '.$notifiable->first_name)
                    ->line('We honour you for taking the first step towards making lasting change with a Sol Cleanse subscription, and look forward to supporting you to be the healthiest version of yourself!')
                    ->line('Starting '.$this->subscription->created_at->format('d/m/Y').', your subscription will automatically renew for $'.$this->subscription->price.'. This is a rolling subscription that will continue every '.$this->subscription->frequency.' weeks.')
                    ->line('To update your payment details, please visit your account.')
                    ->line('Please contact Sol Cleanse customer care if you have any questions about your recurring orders.')
                    ->line('We look forward to fulfilling your order soon and supporting you on your wellness journey.')
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
