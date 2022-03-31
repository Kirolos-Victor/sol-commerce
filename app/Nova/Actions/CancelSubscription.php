<?php

namespace App\Nova\Actions;

use App\Models\AccountData;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Notifications\SubscriptionCancelled;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Textarea;

class CancelSubscription extends Action
{
    use InteractsWithQueue, Queueable;

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $subscription) {
            $subscription->update([
                'cancelled_at' => \Carbon\Carbon::now()
            ]);

            // cancel renewal orders
            $orders = $subscription->orders()->where('status', 'Renewal')->get();
            foreach ($orders as $order) {
                $orders->update(['status' => 'Cancelled']);
            }
            
            $subscription->user->notify(new SubscriptionCancelled($subscription));
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [

        ];
    }
}