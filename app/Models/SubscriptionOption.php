<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionOption extends Model
{
	use HasFactory;
    use SoftDeletes;
	
	protected $fillable = [
		'frequency',
		'price',
		'buffer_days',
		'pause_count',
		'pause_days'
	];

	protected $appends = [
		'title'
	];

	public function variant()
    {
        return $this->belongsTo(Variant::class);
	}
	
	public function getTitleAttribute($value) { return 'Renews every '.$this->frequency.' week'.($this->frequency == 1 ? '' : 's'); }

	public function getPriceAttribute($value) { return $value / 100; }
	public function setPriceAttribute($value) { $this->attributes['price'] = $value * 100; }
}
