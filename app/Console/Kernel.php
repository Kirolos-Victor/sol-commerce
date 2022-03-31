<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Http;

use Carbon\Carbon;

use App\Models\Order;
use App\Models\BlockedDate;

use App\Notifications\ReviewRequest;
use App\Notifications\SubscriptionUpcoming;
use App\Notifications\SubscriptionRenewed;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // delivery dates
        $orders = Order::where('delivery_date',  Carbon::now()->toDateString())->get();
        foreach ($orders as $order) {
            $order->sendToSendle();
        }


        // renew sub orders
        $renewal_orders = Order::where('status', 'Renewal')->get();
        foreach ($renewal_orders as $renewal_order) {

            // send upcoming renewal email
            if ($renewal_order->renewal_date->toDateString() === Carbon::now()->subDays(3)->toDateString()) {
                $renewal_order->user->notify(new SubscriptionUpcoming($renewal_order->subscriptionProfile));
            }

            // renew order
            if ($renewal_order->renewal_date > Carbon::now()) {

                // pay for order and update status
                $payment_method = $renewal_order->user->defaultPaymentMethod();
                $renewal_order->user->charge($renewal_order->total, $payment_method->id);
			    $renewal_order->update(['status' => 'Paid']);
                // todo: send to integromat and klav?

                // get sub profile
                $subscription = $renewal_order->subscriptionProfile;                

                // format new order data
                $order_array = $renewal_order->toArray();
                unset($order_array['id'], $order['payment_intent'], $order['tracking_number'], $order['notes']);
				$order_array['status'] = 'Renewal';
				$order_array['delivery_date'] = $renewal_order->delivery_date->addWeeks($subscription->frequency);

                // get blocked dates for location so we can see if theres any alternate dates
                $location_id = $renewal_order->location_id;
                $blocked_dates = BlockedDate::whereNull('product_id')
                    ->whereNull('category_id')
                    ->where(function($query) use ($location_id) {
                        $query->whereNull('location_id');
                        $query->orWhere('location_id', $location_id);
                })->get();

                // $today = Carbon::now()->toDateString();
                foreach ($blocked_dates as $blocked_date) {
                    $array = explode("\n", $blocked_date->dates);
                    if ($blocked_date->alternate_date) {
                        foreach ($array as $key => $value) { 
                            if ($order_array['delivery_date'] === $value) {
                                $order_array['delivery_date'] = $blocked_date->alternate_date->toDateString(); 
                                break 2;
                            }
                        }
                    }
                }

                // create renewal order
                $order = $renewal_order->user->orders()->create($order_array);

                // create order item
                $total = $subscription->price * $subscription->qty;
				$order->orderItems()->create([
                    'variant_id' => $subscription->variant_id,
                    'subscription_option_id' => $subscription->subscription_option_id,

                    'price' => $subscription->price,
                    'qty' => $subscription->qty,

                    'tax' => $total * 0.1,
                    'subtotal' => $total * 0.9,
                    'total' => $total,
                ]);

                $order->sendToKlav();

                $renewal_order->user->notify(new SubscriptionRenewed($renewal_order->subscriptionProfile));
            }
        }



        // send review email
        $orders = Order::where('status', '!=', 'Cancelled')->where('delivery_date', Carbon::now()->subDays(5)->toDateString())->get();
        foreach ($orders as $order) {
            // skip orders that have reviews already
            if ($order->review) {
                continue;
            }

            // check sub orders, skip if any have a review 
            if ($order->subscription) {
                foreach ($order->subscription->orders as $suborder) {
                    if ($suborder->review) {
                        continue 2;
                    }
                }
            }

            $order->user->notify(new ReviewRequest($order));
        }

        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
