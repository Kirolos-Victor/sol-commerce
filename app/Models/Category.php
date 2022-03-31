<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use ClassicO\NovaMediaLibrary\API;

class Category extends Model
{
	use HasFactory;

	protected $fillable = [
		'title',
		'url',
		'short_description',
		'description',
		'image',
		'faq',
		'footer_quote',
	];
	
	protected $attributes = [

	];

	protected $casts = [
		'faq' => 'array',
	];

	protected $appends = [
		'image_object'
	];

	protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->url = Str::slug($model->title);
        });

		static::updating(function ($model) {
            if (!$model->url) {
				$model->url = Str::slug($model->title);
			}
        });
    }

	public function parent()
    {
		return $this->belongsTo(Category::class, 'parent_id');
	}

	public function subcategories()
    {
		return $this->hasMany(Category::class, 'parent_id');
	}

	public function products()
    {
		return $this->belongsToMany(Product::class)->withTimestamps();
	}

	public function productInformation()
    {
		return $this->belongsToMany(ProductInformation::class)->withTimestamps();
	}

	public function getImageObjectAttribute($value) {
		if ($this->image) {
			return API::getFiles($this->image, null, true);
		}
	}
}
