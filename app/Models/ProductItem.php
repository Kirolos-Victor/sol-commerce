<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use ClassicO\NovaMediaLibrary\API;

class ProductItem extends Model
{
	use HasFactory;
	
	protected $fillable = [
		'title',
		'subtitle',
		'content',
		'nutritional_info'
	];

	protected $appends = [
		'image_object'
	];

	protected $casts = [
		'nutritional_info' => 'array'
	];

	public function getImageObjectAttribute($value) {
		if ($this->image) {
			return API::getFiles($this->image, null, true);
		}
	}

	public function products()
    {
		return $this->belongsToMany(Product::class)->withTimestamps();
	}
}
