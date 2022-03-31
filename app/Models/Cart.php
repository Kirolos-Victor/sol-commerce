<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Auth;

class Cart extends Model
{
	use HasFactory;
	
	protected $fillable = [
		'location_id',
		'order_id',
		'user_id',
		'session_id',

		'non_perishable',
		'step',
		'user_status',

		'email',
		'first_name',
		'last_name',
		'phone',

		'deliver_with_renewal_order',
        'delivery_date',

        'shipping_address',
		'shipping_apartment',
		'shipping_city',
		'shipping_state',
		'shipping_postcode',
		'shipping_country',

		'billing_address',
		'billing_apartment',
		'billing_city',
		'billing_state',
		'billing_postcode',
		'billing_country',

		'shipping_method',
		'shipping_price',

		'discount_code_id',
		'discount_code',
		'discount_amount',

		'payment_option',
		'payment_method',
		'payment_intent',
		'paid',

		'notes',
	];

	protected $appends = [
		'perishable',
		'requires_account',
		'shipping_methods',
		'stripe_key',
	];

	protected $casts = [
		'delivery_date' => 'date',
		'shipping_methods' => 'array'
	];

	protected $with = [
		'cartItems', 'cartItems.variant', 'cartItems.variant.subscriptionOptions', 'cartItems.variant.productAddons', 'cartItems.subscriptionOption'
	];

	public function location()
    {
        return $this->belongsTo(Location::class);
	}
	
	public function order()
    {
        return $this->belongsTo(Order::class);
	}
	
	public function user()
    {
        return $this->belongsTo(User::class);
	}

	public function cartItems()
    {
        return $this->hasMany(CartItem::class);
	}

	// shipping price
	public function getShippingPriceAttribute($value) { return $value / 100; }
	public function setShippingPriceAttribute($value) { $this->attributes['shipping_price'] = $value * 100; }

	// discount amount
	public function getDiscountAmountAttribute($value) { return $value / 100; }
	public function setDiscountAmountAttribute($value) { $this->attributes['discount_amount'] = $value * 100; }

	// perishable
	public function getPerishableAttribute($value) {
		$perishable = false;
		
		foreach ($this->cartItems as $cart_item) {
			if ($cart_item->variant->product->perishable) {
				$perishable = true;
				break;
			}
		}

		return $perishable;
	}

	// requires account
	public function getRequiresAccountAttribute($value) {
		$account_required = false;
		
		foreach ($this->cartItems as $cart_item) {
			if ($cart_item->subscriptionOption) {
				$account_required = true;
				break;
			}
		}

		return $account_required;
	}

	// stripe key
	public function getStripeKeyAttribute($value) {
		if ($this->location_id) {
			return config('app.stripe.'.$this->location_id.'.key');
		} 

		return null;
	}

	// shipping methods
	public function getShippingMethodsAttribute($value) { 
		$shipping_methods = [];
		
		if ($this->perishable) {
			if ($this->deliver_with_renewal_order && $this->deliver_with_renewal_order == 'Yes') {
				$shipping_methods[] = [
					'shipping_method' => 'Free',
					'shipping_price' => 0,
					'description' => 'Free'
				];
			} else {
				$shipping_methods[] = [
					'shipping_method' => 'Perishable',
					'shipping_price' => 19,
					'description' => 'Standard - $19'
				];
			}
		} else {
			if ($this->totals['total'] > 10000) {
				$shipping_methods[] = [
					'shipping_method' => 'Free',
					'shipping_price' => 0,
					'description' => 'Free'
				];
			} else {
				$shipping_methods[] = [
					'shipping_method' => 'Standard',
					'shipping_price' => 5,
					'description' => 'Standard - $5'
				];
			}
		}

		$user = Auth::user();
		if ($user && $user->local_pickup) {
			$shipping_methods[] = [
				'shipping_method' => 'Local Pickup',
				'shipping_price' => 0,
				'description' => 'Local Pickup'
			];
		}
		
		return $shipping_methods; 
	}

	// totals, with blocked dates
	public function getTotalsAttribute($value) {
		$totals = [
			'count' => 0,
			'subtotal' => 0,
			'tax' => 0,
			'discount_amount' => $this->discount_amount,
			'shipping' => 0,
			'total' => 0,
			'grand_total' => 0,
			'perishable' => false,
			'blocked_dates' => [strtotime('now')*1000, strtotime('tomorrow')*1000],
			'sold_out_dates' => []
		];

		//$cart->load('location')->load(['cartItems', 'cartItems.variant', 'cartItems.variant.subscriptionOptions', 'cartItems.variant.productAddons', 'cartItems.subscriptionOption']);
		$cart = $this;

		// get blocked dates for location
		$blocked_dates = BlockedDate::whereNull('product_id')->whereNull('category_id')->where(function($query) use ($cart) {
			$query->whereNull('location_id');
			$query->orWhere('location_id', $cart->location->id);
		})->get();

		$now = strtotime('now');

		foreach ($blocked_dates as $blocked_date) {
			$array = explode("\n", $blocked_date->dates); 

			foreach ($array as $key => $value) {
				$timestamp = strtotime($value);
				if ($now < $timestamp) {
					$totals['blocked_dates'][$timestamp] = $timestamp * 1000;

					// sold out
					if ($blocked_date->sold_out) {
						$date = date('d F Y', $timestamp);
						$totals['sold_out_dates'][$date] = $date;
					}
				}
			}
		}

		//$cart_variant_ids = $cart->cartItems->pluck('variant_id')->toArray();
		foreach ($cart->cartItems as $item_key => $item) {
			$totals['count'] += $item->qty;
			$totals['subtotal'] += $item->price * $item->qty;
			$totals['tax'] += ($item->price * $item->qty) * .1;
			$totals['total'] += $item->price * $item->qty;

			// loop through addons and remove any already in cart aswell as any duplicates
			// if ($item->variant->productAddons) {
			// 	foreach ($item->variant->productAddons as $addon_key => $addon) {
			// 		if (in_array($addon->id, $cart_variant_ids)) {
			// 			unset($cart->cartItems[$item_key]->variant->productAddons[$addon_key]);
			// 		}
			// 		$cart_variant_ids[] = $addon->id; // add addon id so it's not shown again
			// 	}
			// }
			
			// check blocked off dates for perishable products
			if ($item->variant->product->perishable) {
				$totals['perishable'] = true;

				// add blocked dates for products
				$blocked_dates = $item->variant->product->blockedDates()->where(function($query) use ($cart) {
					$query->whereNull('location_id');
					$query->orWhere('location_id', $cart->location->id);
				})->get();
				foreach ($blocked_dates as $blocked_date) {
					$array = explode("\n", $blocked_date->dates); 

					foreach ($array as $key => $value) {
						$timestamp = strtotime($value);
						if ($now < $timestamp) {
							$totals['blocked_dates'][$timestamp] = $timestamp * 1000;
		
							// sold out
							if ($blocked_date->sold_out) {
								$date = date('d F Y', $timestamp);
								$totals['sold_out_dates'][$date] = $date;
							}
						}
					}
				}

				// add blocked dates for categories
				$categories = $item->variant->product->categories;
				if ($categories->count()) {
					$blocked_dates = BlockedDate::whereIn('category_id', $categories->pluck('id'))->where(function($query) use ($cart) {
						$query->whereNull('location_id');
						$query->orWhere('location_id', $cart->location->id);
					})->get();
					foreach ($blocked_dates as $blocked_date) {
						$array = explode("\n", $blocked_date->dates); 

						foreach ($array as $key => $value) {
							$timestamp = strtotime($value);
							if ($now < $timestamp) {
								$totals['blocked_dates'][$timestamp] = $timestamp * 1000;
			
								// sold out
								if ($blocked_date->sold_out) {
									$date = date('d F Y', $timestamp);
									$totals['sold_out_dates'][$date] = $date;
								}
							}
						}
					}
				}
			}
		}

		// reindex array
		$totals['blocked_dates'] = array_values($totals['blocked_dates']);
		$totals['sold_out_dates'] = array_values($totals['sold_out_dates']);

		// discount and shipping are added at checking in inertia middlware
		$totals['grand_total'] = $totals['total'];
		if ($this->discount_amount) {
			$totals['grand_total'] -= $this->discount_amount;
		}
		if ($this->shipping_price) {
			$totals['grand_total'] += $this->shipping_price;
		}

		return $totals;
	}

	public function fillCartWithUser() {
		$user = Auth::user();
		if ($user) {
			$user = $user->toArray();
			unset($user['location_id']); // dont update location
			$this->update($user);
		}
	}

}