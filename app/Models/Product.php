<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use Whitecube\NovaFlexibleContent\Value\FlexibleCast;
use Illuminate\Support\Str;
use ClassicO\NovaMediaLibrary\API;

use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;

class Product extends Model implements Sortable
{
	use HasFlexible;
	use HasFactory;
	use SortableTrait;

	protected $fillable = [
		'title',
		'url',
		'subtitle',
		'description',
		'benefits',
		'image',
        'hover_image',
		'images',
		'product_type',
		'option_name',
		'visible',
		'perishable',
		'enquire_only',

		'custom_options',
		'custom_options_choice',

		'start_date',
		'end_date',
		'content',
		'faq',
		'product_recommendations_heading',
	];

	protected $casts = [
		//'content' => FlexibleCast::class,
		'faq' => 'array',
		'images' => 'array',

		'start_date' => 'date',
		'end_date' => 'date',
	];

	protected $attributes = [
		'visible' => 1
	];

	protected $with = [
		'locations'
	];

	protected $appends = [
		'price_range',
        'image_object',
        'images_object',
        'hover_image_object',
		'rating',
		'tabs'
    ];

	public $sortable = [
		'order_column_name' => 'sort_order',
		'sort_when_creating' => true,
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
		return $this->belongsToMany(Category::class)->withTimestamps();
	}

	public function locations()
    {
		return $this->belongsToMany(Location::class)->withTimestamps();
	}

	public function productRecommendations()
    {
		return $this->belongsToMany(Product::class, 'product_recommendations', 'product_id', 'recommended_product_id')->withTimestamps();
	}

	public function variants()
    {
        return $this->hasMany(Variant::class);
	}

	public function blockedDates()
    {
        return $this->hasMany(BlockedDate::class);
	}

	public function articles()
    {
		return $this->belongsToMany(Article::class)->withTimestamps();
	}

	public function reviews()
    {
		return $this->belongsToMany(Review::class)->withTimestamps()->latest();
	}

	public function productItems()
    {
		return $this->belongsToMany(ProductItem::class)->withTimestamps();
	}

	public function getImageObjectAttribute($value) {
		if ($this->image) {
			return API::getFiles($this->image, null, true);
		}
	}

	public function getImagesObjectAttribute($value) {
		if ($this->images) {
			return API::getFiles($this->images, null, true);
		}
	}

	public function getPriceRangeAttribute($value) {
		if ($this->variants()->count()) {
			if ($this->product_type === 'Variable')
				return number_format(($this->variants()->min('price') / 100), 2).' - '.number_format(($this->variants()->max('price') / 100), 2);

			if ($this->product_type === 'Simple')
				return number_format($this->variants()->first()->price, 2);
		} else {
			return '';
		}
	}

	public function getRatingAttribute($value) {
		return $this->reviews()->avg('rating');
	}

	public function getTabsAttribute($value) {
		$tabs = [];

		if ($this->description) {
			$tabs[] = [
				'title' => 'Description',
				'content' => $this->description,
				'open' => true
			];
		}

		if ($this->benefits) {
			$tabs[] = [
				'title' => 'Benefits',
				'content' => $this->benefits,
				'open' => false
			];
		}

		foreach ($this->categories as $category) {
			foreach ($category->productInformation as $prooduct_information) {
				$tabs[] = [
					'title' => $prooduct_information->title,
					'content' => $prooduct_information->content,
					'open' => false
				];
			}
		}

		if ($this->productRecommendations()->count()) {
			$tabs[] = [
				'title' => 'Boost With',
				'content' => '',
				'open' => true
			];
		}

		return $tabs;
	}

    // public function getContentAttribute() {
    //     return $this->flexible('content');
    // }

	// get images accessor
	// public function getImagesAttribute($value) {
	// 	$images = [];
	// 	$media = $this->getMedia('images');
	// 	foreach ($media as $media_item) {
	// 		$images[] = $media_item->getUrl();
	// 	}
	// 	return $images;
	// }

	// content accessor
	public function getContentAttribute($value) {
		$content = json_decode($value, true);
		if (!empty($content)) {
			foreach ($content as $key1 => $section) {
				if ($section['layout'] === 'item_carousel') {
					foreach ($section['attributes']['slides'] as $key2 => $slide) {
						if ($slide['attributes']['item']) {
							$item = \App\Models\ProductItem::find($slide['attributes']['item']);
							if ($item) {
								$content[$key1]['attributes']['slides'][$key2]['attributes']['image_object'] = $item->image_object;
								$content[$key1]['attributes']['slides'][$key2]['attributes']['heading'] = $item->title;
								$content[$key1]['attributes']['slides'][$key2]['attributes']['subheading'] = $item->subtitle;
								$content[$key1]['attributes']['slides'][$key2]['attributes']['content'] = $item->content;
								// $content[$key1]['attributes']['slides'][$key2]['attributes']['button_url'] = '#';
								// $content[$key1]['attributes']['slides'][$key2]['attributes']['button_text'] = 'Nutritional Info';
								$content[$key1]['attributes']['slides'][$key2]['attributes']['nutritional_info'] = $item->nutritional_info;
							}
						}
					}
				}
			}
		}
		return $content;
	}
    public function getHoverImageObjectAttribute($value) {
        if ($this->hover_image) {
            return API::getFiles($this->hover_image, null, true);
        }
    }
}
