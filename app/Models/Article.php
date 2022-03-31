<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use ClassicO\NovaMediaLibrary\API;

class Article extends Model
{
	use HasFactory;
	
	protected $fillable = [
		'title',
		'url',
		'image',
		'excerpt',
		'content',
		'featured',
		'contain_image',
	];

	protected $casts = [
		//'content' => 'array'
	];

	protected $appends = [
        'image_object',
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

	public function categories()
    {
		return $this->belongsToMany(BlogCategory::class)->withTimestamps();
	}

	public function products()
    {
		return $this->belongsToMany(Product::class)->withTimestamps();
	}
	
	public function getImageObjectAttribute($value) {
		if ($this->image) {
			return API::getFiles($this->image, null, true);
		}
	}
}
