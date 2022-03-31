<?php

namespace App\Nova\Actions;

use App\Models\AccountData;
use App\Notifications\OrderCancelled;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Textarea;

class CancelOrder extends Action
{
    use InteractsWithQueue, Queueable;

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $order) {
            $order->notify(new OrderCancelled($order));
            $order->update([
                'status' => 'Cancelled'
            ]);
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