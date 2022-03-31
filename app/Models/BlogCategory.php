<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use ClassicO\NovaMediaLibrary\API;

class BlogCategory extends Model
{
	use HasFactory;

	protected $fillable = [
		'title',
		'url',
		'description',
		'image'
	];
	
	protected $attributes = [

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

	public function articles()
    {
		return $this->belongsToMany(Article::class)->withTimestamps();
	}

	public function getImageObjectAttribute($value) {
		if ($this->image) {
			return API::getFiles($this->image, null, true);
		}
	}
}
