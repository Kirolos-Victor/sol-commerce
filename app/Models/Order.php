<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasEvents;

use Klaviyo\Klaviyo as Klaviyo;
use Klaviyo\Model\EventModel as KlaviyoEvent;

class Order extends Model
{
	use HasFactory;
	use SoftDeletes;
	use HasEvents;
	
	protected $fillable = [
		'location_id',
		'user_id',
		'subscription_id',
		'discount_code_id',

		'payment_intent',
		'status',

        'email',
		'first_name',
        'last_name',
        'phone',

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
		
		'refund_amount',
		'refund_reason',

		'tracking_number',
		'notes',
	];

	protected $casts = [
		'delivery_date' => 'date'
	];

	protected $appends = [
		'subtotal',
		'tax',
		'total',
		'total_formatted',
		'renewal_date',
		'shipping_address_formatted',
		'billing_address_formatted',
	];

	protected $with = [

	];

	public function location()
    {
        return $this->belongsTo(Location::class);
	}

	public function user()
    {
        return $this->belongsTo(User::class);
	}

	public function subscriptionProfile()
    {
        return $this->belongsTo(SubscriptionProfile::class);
	}

	public function discountCode()
    {
        return $this->belongsTo(DiscountCode::class);
	}

	public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
	}

	public function review()
    {
        return $this->hasOne(Review::class);
    }
	
	// subtotal
	public function getSubtotalAttribute($value) {
		return $this->orderItems()->sum('subtotal') / 100;
	}
	
	// tax
	public function getTaxAttribute($value) {
		return $this->orderItems()->sum('tax') / 100;
	}
	
	// total
	public function getTotalAttribute($value) {
		return (($this->orderItems()->sum('total') - $this->getRawOriginal('discount_amount')) + $this->getRawOriginal('shipping_price')) / 100;
	}

	// shipping price
	public function getShippingPriceAttribute($value) { return $value / 100; }
	public function setShippingPriceAttribute($value) { $this->attributes['shipping_price'] = $value * 100; }

	// discount amount
	public function getDiscountAmountAttribute($value) { return $value / 100; }
	public function setDiscountAmountAttribute($value) { $this->attributes['discount_amount'] = $value * 100; }

	// refund amount
	public function getRefundAmountAttribute($value) { return $value / 100; }
	public function setRefundAmountAttribute($value) { $this->attributes['refund_amount'] = $value * 100; }

	// total formatted
	public function getTotalFormattedAttribute($value) {
		return '$'.number_format((($this->orderItems()->sum('total') - $this->getRawOriginal('discount_amount')) + $this->getRawOriginal('shipping_price')) / 100, 2);
	}

	// cut off / renewal date
	public function getRenewalDateAttribute($value) {
		return $this->delivery_date ? $this->delivery_date->startOfDay()->subDays($this->status == 'Renewal' ? 4 : 1) : null; 
	}

    public function getShippingAddressFormattedAttribute($value) {
		return ($this->shipping_apartment ? $this->shipping_apartment.' ' : '') . $this->shipping_address . ' ' . $this->shipping_city . ', ' . $this->shipping_state . ' ' . $this->shipping_postcode . ' ' . $this->shipping_country;
	}

	public function getBillingAddressFormattedAttribute($value) {
		if ($this->billing_address) {
			return ($this->billing_apartment ? $this->billing_apartment.' ' : '') . $this->billing_address . ' ' . $this->billing_city . ', ' . $this->billing_state . ' ' . $this->billing_postcode . ' ' . $this->billing_country;
		} else {
			return null;
		}
	}

	public function sendToKlav() {
        $client = new Klaviyo(config('app.klav_private_key'), config('app.klav_public_key'));

		$order_data = [
			'event' => 'Placed Order',
			'customer_properties' => [
				'$email' => $this->email,
				'$first_name' => $this->first_name,
				'$last_name' => $this->last_name,
				'$phone_number' => $this->phone,
			],
			'properties' => [
				'$event_id' => $this->id,
				'$value' => $this->total,
				'OrderId' => $this->id,
				'Categories' => [],
				'ItemNames' => [],
				//'Brands' => [],
				'DiscountCode' => '',
				'DiscountValue' => 0,
				'BillingAddress' => [
					'FirstName' => $this->first_name,
					'LastName' => $this->last_name,
					//'Company' => '',
					'Address1' => ($this->billing_apartment ? $this->billing_apartment.' ' : '').$this->billing_address,
					'City' => $this->billing_city,
					'Region' => $this->billing_state,
					'Country' => $this->billing_country,
					'CountryCode' => 'AU',
					'Zip' => $this->billing_postcode,
					'Phone' => $this->phone
				],
				'ShippingAddress' => [
					'FirstName' => $this->first_name,
					'LastName' => $this->last_name,
					//'Company' => '',
					'Address1' => ($this->shipping_apartment ? $this->shipping_apartment.' ' : '').$this->shipping_address,
					'City' => $this->shipping_city,
					'Region' => $this->shipping_state,
					'Country' => $this->shipping_country,
					'CountryCode' => 'AU',
					'Zip' => $this->shipping_postcode,
					'Phone' => $this->phone
				],
				'OrderType' => ($this->subscriptionProfile ? ($this->subscriptionProfile->orders()->oldest()->first()->id == $this->id ? 'Subscription' : 'Renewal') : 'One-Off'),
				'DeliveryDate' => $this->delivery_date->format('Y-m-d'),
			],
			'time' => time()
		];

		$all_categories = $item_names = [];
		foreach ($this->orderItems as $order_item) {
			$categories = $order_item->variant->product->categories()->pluck('title')->toArray();
			$all_categories = array_merge($all_categories, $categories);
			$item_names[] = $order_item->variant->product->title;

			$order_data['properties']['Items'][] = [
				'ProductID' => $order_item->variant->product->id,
				'SKU' => $order_item->variant->sku,
				'ProductName' => $order_item->variant->product->title,
				'Quantity' => $order_item->qty,
				'ItemPrice' => $order_item->price,
				'RowTotal' => $order_item->total,
				'ProductURL' => url($order_item->variant->product->url),
				'ImageURL' => $order_item->variant->product->image_object->url,
				'Categories' => $categories
			];

			if (strpos($order_item->variant->product->title, 'Cleanse') !== false) {
				$order_data['properties']['CleanseDays'][] = $order_item->variant->option_value[0];
			}
		}

		$order_data['properties']['Categories'] = array_unique($all_categories, SORT_REGULAR);
		$order_data['properties']['ItemNames'] = $item_names;
		
		$event = new KlaviyoEvent($order_data);
		$client->publicAPI->track($event, true);

		// ordered products
		foreach ($this->orderItems as $order_item) {
			$product_data = [
				'event' => 'Ordered Product',
				'customer_properties' => [
					'$email' => $this->email,
					'$first_name' => $this->first_name,
					'$last_name' => $this->last_name,
					'$phone_number' => $this->phone,
				],
				'properties' => [
					'$event_id' => $this->id.'_'.$order_item->variant->product->sku,
					'$value' => $order_item->total,
					'OrderId' => $this->id,

					'ProductID' => $order_item->variant->product->id,
					'SKU' => $order_item->variant->product->sku,
					'ProductName' => $order_item->variant->product->title,
					'Quantity' => $order_item->qty,
					'ProductURL' => url($order_item->variant->product->url),
					'ImageURL' => $order_item->variant->product->image_object->url,
				],
				'time' => time()
			];

			$event = new KlaviyoEvent($product_data);
			$client->publicAPI->track($event, true);
		}
	}

	public function sendToSendle() {
		$grams = 0;
		foreach ($this->orderItems as $order_item) {
			if ($order_item->variant->weight) {
				$grams += $order_item->variant->weight;
			}
		}

		Http::withBasicAuth('SANDBOX_accounts_italicsbold', 'sandbox_PMJDc26mjz63pdH3C2jTK6wy')->post('https://sandbox.sendle.com/api/orders', [
			'pickup_date' => $this->delivery_date,
			'first_mile_option' => 'pickup', // 'drop off'
			'description' => 'Solclense Order #'.$this->id,
			'weight' => [
				'value' => $grams,
				'units' => 'g',
			],
			'customer_reference' => 'Solclense Order #'.$this->id,
			'metadata' => [
				
			],
			'sender' => [
				'contact' => [
					'name' => 'Sol Cleanse',
					'phone' => '1300 475 202',
					'company' => 'Sol Cleanse',
				],
				'address' => [
					'address_line1' => '6/3 Villiers Dr',
					'suburb' => 'Currumbin Waters',
					'state_name' => 'QLD',
					'postcode' => '4223',
					'country' => 'Australia',
				],
				'instructions' => 'Call unit 3',
			],
			'receiver' => [
				'contact' => [
					'name' => $this->user->name,
					'email' => $this->user->email,
				],
				'address' => [
					'address_line1' => $this->user->shipping_address,
					'suburb' => $this->user->shipping_city,
					'state_name' => $this->user->shipping_state,
					'postcode' => $this->user->shipping_postcode,
					'country' => $this->user->shipping_country,
				],
				'instructions' => 'Leave at door',
			],
		]);
	}

	public function orderDetailsEmail() {
		$order_details = 
        '<div class="table">
            <table>
                <thead>
                    <tr>
                        <th colspan="2">Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($this->orderItems as $orderItem) {
            $order_details .= 
            '<tr>
                <td>
                    '.($orderItem->variant->product->image_object ? '<img style="width: 60px" src="'.$orderItem->variant->product->image_object->url.'" alt="">' : '').'
                </td>
                <td>
                    '.$orderItem->variant->title.'
                </td>
                <td>
                    $'.$orderItem->price.'
                </td>
                <td>
                    '.$orderItem->qty.'
                </td>
            </tr>';
        }

        $order_details .= 
                '</tbody>
            </table>
        </div>';

		return $order_details;
	}

	public function ordertotalsEmail() {
		$order_totals = 
        '<div class="table">
            <table>
                <tbody>
                    <tr>
                        <td>
                            Subtotal inc. gst
                        </td>
                        <td>
                            $'.$this->subtotal.'
                        </td>
                    </tr>';
                    
        if ($this->discount_amount) {
            $order_totals .= 
            '<tr>
                <td>
                    Discount
                </td>
                <td>
                    -$'.$this->discount_amount.'
                </td>
            </tr>';
        }
                    
        $order_totals .= 
                    '<tr>
                        <td>
                            Shipping
                        </td>
                        <td>
                            $'.$this->shipping_price.'
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Total
                        </td>
                        <td>
                            $'.$this->total.'
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>';

		return $order_totals;
	}
}