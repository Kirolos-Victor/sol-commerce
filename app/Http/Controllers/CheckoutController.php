<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;

use App\Models\Cart;
use App\Models\DiscountCode;

use App\Models\User;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
		$user = Auth::user();

		$session_id = $request->session()->getId();
		$cart = Cart::where('session_id', $session_id)->where('paid', 0)->first();

		if (!$cart) {
			return redirect('/shop');
		}

        config(['cashier.key' => config('app.stripe.'.$cart->location_id.'.key')]);
        config(['cashier.secret' => config('app.stripe.'.$cart->location_id.'.secret')]);

		// check if customer is setup in this stripe location. 
		if ($user && $user->location_id && $user->location_id == $cart->location_id) {
			$stripe = new \Stripe\StripeClient(config('cashier.secret'));
			$customers = $stripe->customers->all(['email' => $user->email]);
			if ($customers->data) {
				// add payment method id if exists
				if ($user->hasDefaultPaymentMethod()) {
					try {
						$payment_method = $user->defaultPaymentMethod();
						$user->payment_method = $payment_method->id;
					} catch (Exception $e) {
						
					}
				}
			}
		}

		// get renewal orders incase user has subscription with a product renewing soon. allows to combine shipping with the sub order
		$renewal_orders = [];
		if ($user && $cart->perishable) {
			$renewal_orders = $user->orders()->where('delivery_date', '>', date('Y-m-d'))->get();
		}

		return \Inertia\Inertia::render('Checkout', [
			'user' => $user,
			'renewal_orders' => $renewal_orders
		]);
    }

	public function store(Request $request)
    {
		$data = $request->all();
		$user = Auth::user();

		$session_id = $request->session()->getId();
		$cart = Cart::where('session_id', $session_id)->where('paid', 0)->first();

		// validate step
		switch ($data['step']) {
			case 1:
				$request->validate([
					'email' => ['required', 'email'],
				]);
				
				if (!$user) {
					// check if user exists and has a password
					$user = User::where('email', $data['email'])->whereNotNull('password')->first();
					if ($user) {
						$cart->update(['user_status' => 'Has Account']);
						return redirect()->back()->withErrors([
							'asd' => 'You already have an account with Solcleanse. Please login to complete checkout.'
						]);
					}

					// check if user exists and doesnt have a password
					$user = User::where('email', $data['email'])->whereNull('password')->first();
					if ($user) {
						$data['user_status'] = 'No Password'; // set has account to true but let them through, this will be a guest checkout
					}
				}
			break;
			case 2:
				$request->validate([
					'email' => ['required', 'email'],

					'delivery_date' => $cart->perishable ? ['required', 'date'] : [], //already validated from date picker?
					'first_name' => 'required',
					'last_name' => 'required',
					'phone' => 'required',

					'shipping_address' => 'required',
					'shipping_postcode' => 'required',
					'shipping_city' => 'required',
					'shipping_state' => 'required',
					'shipping_country' => 'required',

					'billing_address' => 'required_if:billing_address_same,0',
					'billing_postcode' => 'required_if:billing_address_same,0',
					'billing_city' => 'required_if:billing_address_same,0',
					'billing_state' => 'required_if:billing_address_same,0',
					'billing_country' => 'required_if:billing_address_same,0',
				]);
			break;	
			case 3:
				$request->validate([
					'shipping_method' => 'required',
				]);

				switch ($data['shipping_method']) {
					case 'Perishable':
						$data['shipping_price'] = 19;
					break;
					case 'Standard':
						$data['shipping_price'] = 5;
					break;
					case 'Free':
						$data['shipping_price'] = 0;
					break;
					case 'Local Pickup':
						$data['shipping_price'] = 0;
					break;
				}

				if ($data['discount_code']) {
					$discount_code = DiscountCode::where('code', strtolower($data['discount_code']))->first();
					if ($discount_code) {

						// total uses
						if ($discount_code->total_uses && $discount_code->orders()->count() > $discount_code->total_uses) {
							return redirect()->back()->withErrors([
								'discount_code' => 'Disount code is no longer valid'
							]);
						}

						// start & end date
						$now = Carbon::now();
						if (
							($discount_code->start_date && $discount_code->start_date > $now) || 
							($discount_code->end_date && $discount_code->end_date < $now)
						) {
							return redirect()->back()->withErrors([
								'discount_code' => 'Disount code is no longer valid'
							]);
						}

						// min spend
						if ($discount_code->min_spend && $cart->totals['total'] <= $discount_code->min_spend) {
							return redirect()->back()->withErrors([
								'discount_code' => 'Discount code requires your order to be $'.$discount_code->min_spend
							]);
						}

						$data['discount_code_id'] = $discount_code->id;

						switch ($discount_code->discount_type) {
							case 'Percentage';
								$discount_amount = ($cart->totals['subtotal']) * ($discount_code->amount / 100);
							break;
							case 'Value';
								$discount_amount = $discount_code->amount;
							break;
						}

						$data['discount_amount'] = $discount_amount;
					} else {
						return redirect()->back()->withErrors([
							'discount_code' => 'Disount code is not valid'
						]);
					}
				}
			break;
		}
		
		$cart->update($data);

		// calculate shipping method - todo make a model function?
		// if ($cart->perishable) {
		// 	if ($cart->deliver_with_renewal_order == 'Yes') {
		// 		$cart->update([
		// 			'shipping_method' => 'Free',
		// 			'shipping_price' => 0
		// 		]);
		// 	} else {
		// 		$cart->update([
		// 			'shipping_method' => 'Perishable',
		// 			'shipping_price' => 19,
		// 		]);
		// 	}
		// } else {
		// 	if ($cart->totals['total'] > 100) {
		// 		$cart->update([
		// 			'shipping_method' => 'Free',
		// 			'shipping_price' => 0
		// 		]);
		// 	} else {
		// 		$cart->update([
		// 			'shipping_method' => 'Standard',
		// 			'shipping_price' => 5
		// 		]);
		// 	}
		// }

		return redirect()->back();
    }
}
