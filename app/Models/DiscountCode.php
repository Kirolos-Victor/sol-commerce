<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
	use HasFactory;
	
	protected $fillable = [
		'title',
		'code',
		'discount_type',
		'amount',
		
		'total_uses',
		'start_date',
		'end_date',
		'min_spend',
	];
	
	protected $appends = [
		'current_uses'
	];

	protected $casts = [
		'start_date' => 'date',
		'end_date' => 'date',
	];

	public function orders()
    {
        return $this->hasMany(Order::class);
	}

	public function getCurrentUsesAttribute($value) { return $this->orders()->count(); }

	public function getMinSpendAttribute($value) { return ($value ? $value / 100 : null); }
	public function setMinSpendAttribute($value) { $this->attributes['min_spend'] = $value * 100; }
}