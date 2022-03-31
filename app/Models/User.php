<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Cashier\Billable;
//use App\Models\Traits\MyBillable;

use App\Notifications\CustomResetPassword;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location_id',
        
        'email',
		'first_name',
        'last_name',
        'phone',

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

		'notes',
        'password',

        'local_pickup',
        'admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

	protected $appends = [
        'name',
		'shipping_address_formatted',
		'billing_address_formatted',
        'stripe_key'
	];

	public function orders()
    {
        return $this->hasMany(Order::class);
	}

	public function subscriptionProfiles()
    {
        return $this->hasMany(SubscriptionProfile::class);
	}

    public function getNameAttribute($value) {
		return $this->first_name.($this->last_name ? ' '.$this->last_name : '');
	}

    public function getShippingAddressFormattedAttribute($value) {
		return ($this->shipping_apartment ? $this->shipping_apartment.' ' : '') . $this->shipping_address . ' ' . $this->shipping_city . ', ' . $this->shipping_state . ' ' . $this->shipping_postcode . ' ' . $this->shipping_country;
	}

	public function getBillingAddressFormattedAttribute($value) {
		return ($this->billing_apartment ? $this->billing_apartment.' ' : '') . $this->billing_address . ' ' . $this->billing_city . ', ' . $this->billing_state . ' ' . $this->billing_postcode . ' ' . $this->billing_country;
	}

    // stripe key
	public function getStripeKeyAttribute($value) {
		if ($this->location_id) {
			return config('app.stripe.'.$this->location_id.'.key');
		} 

		return null;
	}

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }
}
