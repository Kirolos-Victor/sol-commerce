<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
	use HasFactory;
    use SoftDeletes;

	protected $fillable = [
		'variant_id',
		'subscription_option_id',
		'price',
		'qty',
		'discount',
		'account',
		'tax_code',
		'tax_rate',
		'tax',
		'subtotal',
		'total',
		'custom_options'
	];

	protected $with = [

	];

	protected $appends = [

	];

	protected $casts = [
		'custom_options' => 'array'
	];

	public function order()
    {
        return $this->belongsTo(Order::class);
	}

	public function variant()
    {
        return $this->belongsTo(Variant::class);
	}

	public function subscriptionOption()
    {
        return $this->belongsTo(SubscriptionOption::class);
	}

	public function getPriceAttribute($value) { return $value / 100; }
	public function setPriceAttribute($value) { $this->attributes['price'] = $value * 100; }

	public function getSubtotalAttribute($value) { return $value / 100; }
	public function setSubtotalAttribute($value) { $this->attributes['subtotal'] = $value * 100; }

	public function getTaxAttribute($value) { return $value / 100; }
	public function setTaxAttribute($value) { $this->attributes['tax'] = $value * 100; }

	public function getTotalAttribute($value) { return $value / 100; }
	public function setTotalAttribute($value) { $this->attributes['total'] = $value * 100; }
}