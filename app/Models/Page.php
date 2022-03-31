<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;

class Page extends Model
{
	use HasFactory;
	
	protected $fillable = [
		'title',
		'url',
		'content',
	];

	protected $casts = [
		'content' => 'array'
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
}
