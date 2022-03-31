<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class SubscriptionProfile extends Model
{
	use HasFactory, SoftDeletes;
	
	protected $fillable = [
		'location_id', 
		'variant_id', 
		'subscription_option_id',

		'frequency',
		'price',
		'qty',

		'buffer_days',
		'pause_count',
		'pause_days',

		'paused_count',

		'cancelled_at'
	];

	protected $casts = [
		'buffer_date' => 'date',
		'paused_date' => 'date'
	];

	protected $appends = [
		'buffer_date',
		'paused_date'
	];

	public function location()
    {
        return $this->belongsTo(Location::class);
	}

	public function user()
    {
        return $this->belongsTo(User::class);
	}

	public function variant()
    {
        return $this->belongsTo(Variant::class);
	}

	public function subscriptionOption()
    {
        return $this->belongsTo(SubscriptionOption::class);
	}

	public function orders()
    {
        return $this->hasMany(Order::class, 'subscription_id', 'id')->where('status', '!=', 'Renewal');
	}

	public function nextOrder()
    {
        return $this->hasOne(Order::class, 'subscription_id', 'id')->where('status', '=', 'Renewal');
	}

	public function getPriceAttribute($value) { return $value / 100; }
	public function setPriceAttribute($value) { $this->attributes['price'] = $value * 100; }

	public function getBufferDateAttribute($value) {
		return Carbon::now()->addDays($this->buffer_days);
	}

	public function getPausedDateAttribute($value) {
		return Carbon::now()->addDays($this->pause_days);
	}
}