<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedDate extends Model
{
	use HasFactory;
	
	protected $fillable = [
		'dates',
		'alternate_date',
		'sold_out'
	];

	protected $dates = [
		'alternate_date'
	];

	// protected $appends = [
	// 	'dates_array'
	// ];

	public function location()
    {
        return $this->belongsTo(Location::class);
	}

	public function category()
    {
        return $this->belongsTo(Category::class);
	}
	
	public function product()
    {
        return $this->belongsTo(Product::class);
	}

	// public function getDatesArrayAttribute($value) {
	// 	return explode("\n", $this->dates); 
	// }
}