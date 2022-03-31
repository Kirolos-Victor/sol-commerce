<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;

use Carbon\Carbon;

use App\Notifications\OrderConfirmation;
use App\Notifications\SubscriptionConfirmed;
use Illuminate\Support\Facades\Http;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
		$data = $request->all();
		$user = Auth::user();

		$orders = $user->orders()->latest()->get();

		return \Inertia\Inertia::render('Account/Orders', [
			'orders' => $orders
		]);
    }

	public function store(Request $request)
    {
		$data = $request->all();
		$user = Auth::user();

		$session_id = $request->session()->getId();
		$cart = Cart::where('session_id', $session_id)->whereNull('order_id')->first();
        config(['cashier.key' => config('app.stripe.'.$cart->location_id.'.key')]);
        config(['cashier.secret' => config('app.stripe.'.$cart->location_id.'.secret')]);

		$request->validate([
			'email' => ['required', 'email'],
			'first_name' => 'required',
			'last_name' => 'required',
			'phone' => 'required',
			
			'shipping_address' => 'required',
			'shipping_postcode' => 'required',
			'shipping_city' => 'required',
			'shipping_state' => 'required',
			'shipping_country' => 'required',
		]);

		$data['location_id'] = $cart->location_id;
		$data['status'] = 'Pending Payment';

		// create or update user
		if (!$user) {
			$user = User::where('email', $data['email'])->first();
			if (!$user) {
				// create new user
				$user = User::create($data);
			} else {
				// found a user by email but they're not logged in, update their details anyway?
				$user->update($data);
			}
		} else {
			// update user details with new request
			$user->update($data);
		}
		
		// format data
		$data['delivery_date'] = Carbon::parse($data['delivery_date']);
		$data['shipping_price'] = $cart->shipping_price;
		$data['discount_amount'] = $cart->discount_amount;

		// create order
		$order = $user->orders()->create($data);

		// order id on cart
		$cart->update(['order_id' => $order->id]);

		foreach ($cart->cartItems as $cart_item) {
			$total = $cart_item->price * $cart_item->qty;

			// create order item
			$order_item = [
				'variant_id' => $cart_item->variant_id,
				'subscription_option_id' => $cart_item->subscription_option_id,

				'price' => $cart_item->price,
				'qty' => $cart_item->qty,

				'tax' => $total * 0.1,
				'subtotal' => $total,
				'total' => $total,

				'custom_options' => $cart_item->custom_options
			];

			$order->orderItems()->create($order_item);

			// create subscription and renewal order
			if ($cart_item->subscription_option_id) {
				$next_order = $order->delivery_date->addWeeks($cart_item->subscriptionOption->frequency);

				$subscription = $user->subscriptionProfiles()->create([
					'location_id' => $cart->location_id,
					'variant_id' => $cart_item->variant_id,
					'subscription_option_id' => $cart_item->subscription_option_id,

					'frequency' => $cart_item->subscriptionOption->frequency,
					'price' => $cart_item->subscriptionOption->price,
					'qty' => $cart_item->qty,

					'buffer_days' => $cart_item->subscriptionOption->buffer_days,
					'pause_count' => $cart_item->subscriptionOption->pause_count,
					'pause_days' => $cart_item->subscriptionOption->pause_days
				]);

				$order->update(['subscription_id' => $subscription->id]);

				// create Renewal order here
				$renewal_order = $data;
				$renewal_order['subscription_id'] = $subscription->id;
				$renewal_order['status'] = 'Renewal';
				$renewal_order['delivery_date'] = $next_order;
				
				$renewal_order = $user->orders()->create($renewal_order);
				$renewal_order->orderItems()->create($order_item);

				$order->user->notify(new SubscriptionConfirmed($subscription));
			}
		}

		// check payment succeeded if not afterpay and mark order paid
		if ($data['payment_option'] !== 'Afterpay') {
			$stripe = new \Stripe\StripeClient(config('cashier.secret'));
			$payment_intent = $stripe->paymentIntents->retrieve($data['payment_intent']);
			if ($payment_intent->status !== 'succeeded') {
				$request->validate([
					'card' => ['required'],
				]);
			}
			
			$cart->update(['paid' => 1]);
			$order->update(['status' => 'Paid']);
			$order->sendToKlav();
			$order->user->notify(new OrderConfirmation($order));
			Http::post('https://hook.integromat.com/wturuyylbchh24h1mpsv8qdwjlj5hste', $order->toArray());

			// create new session
			$request->session()->regenerate();

			return redirect('/orders/'.$order->id.'?source=checkout');
		}

		return redirect()->back();
    }

	public function update(Order $order, Request $request)
    {
		$data = $request->all();
		$user = Auth::user();

		$session_id = $request->session()->getId();
		$cart = Cart::where('session_id', $session_id)->where('paid', 0)->first();
        config(['cashier.key' => config('app.stripe.'.$cart->location_id.'.key')]);
        config(['cashier.secret' => config('app.stripe.'.$cart->location_id.'.secret')]);

		// check payment succeeded
		$stripe = new \Stripe\StripeClient(config('cashier.secret'));
		$payment_intent = $stripe->paymentIntents->retrieve($data['payment_intent']);
		if ($payment_intent->status !== 'succeeded') {
			$request->validate([
				'card' => ['required'],
			]);
		}

		$cart->update(['paid' => 1]);
		$cart->order->update(['status' => 'Paid']);
		$cart->order->sendToKlav();
		$order->user->notify(new OrderConfirmation($order));
		Http::post('https://hook.integromat.com/wturuyylbchh24h1mpsv8qdwjlj5hste', $cart->order->toArray());

		// create new session
        $request->session()->regenerate();

		return redirect()->back();
	}
	
	public function show(Order $order, Request $request)
    {
		$data = $request->all();
		// $user = Auth::user();

		$order->load('orderItems', 'orderItems.variant', 'review');

		// $response = Http::post('https://hook.integromat.com/wturuyylbchh24h1mpsv8qdwjlj5hste', $order->toArray());
		// dd($response);
		
		return \Inertia\Inertia::render('Account/Order', [
			'order' => $order,
			'source' => !empty($data['source']) ? $data['source'] : null
		]);
    }

	public function review(Order $order, Request $request)
    {
		$request->validate([
			'rating' => ['required'],
			'review' => ['required'],
		]);

		$data = $request->all();
		$data['user_id'] = Auth::id();

		$review = $order->review()->create($data);

		foreach ($order->orderItems as $order_item) {
			$review->products()->attach($order_item->variant->product);
		}

		return redirect()->back();
    }
}
