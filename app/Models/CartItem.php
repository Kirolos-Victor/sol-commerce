<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
	use HasFactory;
	
	protected $fillable = [
		'cart_id',
		'variant_id',
		'subscription_option_id',
		'qty',
		'custom_options'
	];
	
	protected $appends = [
		'price',
		'expanded'
	];

	protected $casts = [
		'custom_options' => 'array'
	];

	public function cart()
    {
        return $this->belongsTo(Cart::class);
	}

	public function variant()
    {
        return $this->belongsTo(Variant::class);
	}

	public function subscriptionOption()
    {
        return $this->belongsTo(SubscriptionOption::class);
	}

	public function getPriceAttribute($value) {
		return $this->subscription_option_id ? $this->subscriptionOption->price : $this->variant->price;
	}

	public function getExpandedAttribute($value) {
		return false;
	}
}