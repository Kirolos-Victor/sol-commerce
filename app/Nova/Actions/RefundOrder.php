<?php

namespace App\Nova\Actions;

use App\Models\AccountData;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Textarea;

class RefundOrder extends Action
{
    use InteractsWithQueue, Queueable;

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $order) {
            config(['cashier.key' => config('app.stripe.'.$order->location_id.'.key')]);
            config(['cashier.secret' => config('app.stripe.'.$order->location_id.'.secret')]);

            $stripe = new \Stripe\StripeClient(config('cashier.secret'));
            $refund = $stripe->refunds->create([
                'payment_intent' => $order->payment_intent,
                'amount' => (int)$fields->refund_amount*100,
            ]);

            $order->update([
                'refund_amount' => $fields->refund_amount,
                'refund_reason' => $fields->refund_reason,
            ]);

            $order->notify(new OrderRefunded($order));
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
            Currency::make('Refund Amount'),
            Textarea::make('Refund Reason'),
        ];
    }
}