<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StripeController extends Controller
{

    public function setupIntent(Request $request)
    {
        $data = $request->all();

        $session_id = $request->session()->getId();
		$cart = Cart::where('session_id', $session_id)->where('paid', 0)->first();

        $user = Auth::user();
        if ($cart) {
            $location_id = $cart->location_id;
        } else {
            $location_id = $user->location_id;
        }

        config(['cashier.key' => config('app.stripe.'.$location_id.'.key')]);
        config(['cashier.secret' => config('app.stripe.'.$location_id.'.secret')]);

        // create user
        $user = Auth::user();
		if (!$user) {
			$user = User::where('email', $data['email'])->first();
			if (!$user) {
				// create new user
				$user = User::create($data);
			}
		}

        $intent = $user->createSetupIntent();

        return response()->json([
            'clientSecret' => $intent->client_secret
        ]);
    }

    public function addPaymentMethod(Request $request) {
        $data = $request->all();

        // get or create user
        $user = Auth::user();
		if (!$user) {
			$user = User::where('email', $data['email'])->first();
			if (!$user) {
				// create new user
				$user = User::create($data);
			}
		}

        $user->createOrGetStripeCustomer();
        $payment_method = $user->updateDefaultPaymentMethod($data['payment_method']);

        return response()->json([
            'payment_method' => $payment_method
        ]);
    }

    public function paymentIntent(Request $request)
    {
        $data = $request->all();

        $session_id = $request->session()->getId();
		$cart = Cart::where('session_id', $session_id)->where('paid', 0)->first();
        config(['cashier.key' => config('app.stripe.'.$cart->location_id.'.key')]);
        config(['cashier.secret' => config('app.stripe.'.$cart->location_id.'.secret')]);

        $stripe = new \Stripe\StripeClient(config('cashier.secret'));
        $intent = $stripe->paymentIntents->create([
            'amount' => $cart->totals['grand_total'] * 100,
            'currency' => 'aud',
            'payment_method_types' => ['card', 'afterpay_clearpay'],
            'shipping' => [
				'name' => $data['first_name'].' '.$data['last_name'],
				'address' => [
					'line1' => $data['shipping_address'],
					'city' => $data['shipping_city'],
					'state' => $data['shipping_state'],
					'country' => $data['shipping_country'],
					'postal_code' => $data['shipping_postcode'],
				],
			]
        ]);

        return response()->json([
            'clientSecret' => $intent->client_secret
        ]);
    }

    public function chargeUser(Request $request) {
        $data = $request->all();

        $session_id = $request->session()->getId();
        $cart = Cart::where('session_id', $session_id)->first();
        config(['cashier.key' => config('app.stripe.'.$cart->location_id.'.key')]);
        config(['cashier.secret' => config('app.stripe.'.$cart->location_id.'.secret')]);

        // get or create user
        $user = Auth::user();
        if (!$user) {
            $user = User::where('email', $data['email'])->first();
            if (!$user) {
                // create new user
                $user = User::create($data);
            }
        }
        
        $payment = false;
        try {
            $payment = $user->charge($cart->totals['grand_total'] * 100, $data['payment_method']); //todo fix total
        } catch (Exception $e) {
            
        }

        return response()->json([
            'payment' => $payment
        ]);
    }

    public function afterpay(Request $request) {
        $data = $request->all();

		$session_id = $request->session()->getId();
		$cart = Cart::where('session_id', $session_id)->where('paid', 0)->first();
        config(['cashier.key' => config('app.stripe.'.$cart->location_id.'.key')]);
        config(['cashier.secret' => config('app.stripe.'.$cart->location_id.'.secret')]);

		// check payment succeeded
		$stripe = new \Stripe\StripeClient(config('cashier.secret'));
		$payment_intent = $stripe->paymentIntents->retrieve($data['payment_intent']);
		if ($payment_intent->status !== 'succeeded') {
            // todo go back to order with error? 
		}

		$cart->update(['paid' => 1]);
		$cart->order->update(['status' => 'Paid']);
		$cart->order->sendToKlav();
		$response = Http::post('https://hook.integromat.com/wturuyylbchh24h1mpsv8qdwjlj5hste', $cart->order->toArray());

		return redirect('orders/'.$cart->order->id.'?source=checkout');
    }
}