<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

use ClassicO\NovaMediaLibrary\API;

class Variant extends Model implements Sortable
{
	use HasFactory;
	use SortableTrait;

	protected $fillable = [
		'option_value',
		'price',
		'sku',
		'available',
		'weight',
		'length',
		'width',
		'height',
		'description',
		'visible',
	];

	protected $appends = [
		'title',
		'product_type',
		'image_object',
		'allocated'
	];

	protected $attributes = [
		'visible' => 1
	];

	public $sortable = [
		'order_column_name' => 'sort_order',
		'sort_when_creating' => true,
		'sort_on_has_many' => true,
	];

	public function product()
    {
        return $this->belongsTo(Product::class);
	}

	public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
	}

	public function cart()
    {
        return $this->belongsToMany(User::class, 'cart')->withPivot('qty')->withTimestamps();
	}

	public function subscriptionOptions()
    {
        return $this->hasMany(SubscriptionOption::class);
	}	

	public function productAddons()
    {
		return $this->belongsToMany(Variant::class, 'product_addons', 'variant_id', 'addon_variant_id')->withTimestamps();
	}

	public function getTitleAttribute($value) { 
		$product = $this->product;
		switch ($product->product_type) {
			case 'Simple': 
				return $product->title;
			break;
			case 'Variable': 
				return $product->title.': '.$this->option_value;
			break;
		}
	}

	public function getProductTypeAttribute($value) { $product = $this->product; return $product ? $product->product_type : null; }
	public function getPriceAttribute($value) { return $value / 100; }
	public function setPriceAttribute($value) { $this->attributes['price'] = $value * 100; }

	public function getAllocatedAttribute() {
		return 0;
	}

	public function getImageObjectAttribute($value) {
		if ($this->image) {
			return API::getFiles($this->image, null, true);
		}
	}
}