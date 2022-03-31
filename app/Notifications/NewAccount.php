<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class NewAccount extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {

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
                    ->subject('Your Sol Cleanse account has been created')
					->greeting('Hi '.$notifiable->first_name)
                    ->line('Thank you for creating an account with Sol Cleanse.')
                    ->line('You can access your account area to view orders and subscriptions, change your password, update payment details as well as access your daily cleanse meditations, rituals and guided mindfulness activity workbooks.')
                    ->line(new HtmlString('If there is any way we can help you further, please contact Sol Cleanse <a href="'.url('/contact').'">customer care</a>.'))
                    ->line('We look forward to supporting you on your wellness journey.')
                    ->line('With Gratitude ~')
					->salutation('Sol Cleanse')
                    ->action('Visit your account', url('/account'));
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
