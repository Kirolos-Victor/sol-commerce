<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
	use HasFactory;

	protected $fillable = [			
		'name',
		'email',
		'phone',
		'postcodes',

		'stripe_pk',

		'address',
		'apartment',
		'city',
		'state',
		'postcode',
		'country',
		'latitude',
		'longitude',
	];

	protected $hidden = [
        'stripe_sk',
    ];

	protected $with = [

	];

	protected $appends = [
		'address_formatted',
	];

	public function orders()
    {
        return $this->hasMany(Order::class);
	}

	public function products()
    {
		return $this->belongsToMany(Product::class)->withTimestamps();
	}

	public function getAddressFormattedAttribute($value) {
		return ($this->apartment ? $this->apartment.' ' : '') . $this->address . ' ' . $this->city . ', ' . $this->state . ' ' . $this->postcode . ' ' . $this->country;
	}
}
