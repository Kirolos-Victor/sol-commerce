<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsTo;

use ClassicO\NovaMediaLibrary\MediaLibrary;
use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
use Benjacho\BelongsToManyField\BelongsToManyField;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;

use App\Models\Product;

class Variant extends Resource
{
    use HasSortableRows;
    
	public static $displayInNavigation = false;
	
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Variant::class;

	public static $with = ['product'];

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
	public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'option_value', 'sku',
    ];

	public static $searchRelations = [
		'product' => ['id', 'title'],
	];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
		$fields = [];

        $fields[] = Text::make('Title', function () {
			return $this->title;
		})->detailLink();

		$fields[] = BelongsTo::make('Product');

		if (
            ($this->resource->exists && $this->resource->product_type === 'Variable') || 
            ($request->viaResourceId && Product::find($request->viaResourceId) && Product::find($request->viaResourceId)->product_type === 'Variable')
        ) {
			$fields[] = Text::make('Option Value')->sortable();
		}

        $fields[] = MediaLibrary::make('Image')->preview('thumb');

		$fields[] = Currency::make('Price')->step(0.01)->sortable();
		$fields[] = Text::make('SKU')->sortable();
		$fields[] = Number::make('Available')->sortable();
		$fields[] = Number::make('Allocated')->sortable()->readOnly();

		$fields[] = Number::make('Weight (g)', 'weight')->sortable();
		$fields[] = Number::make('Length (mm)', 'length')->sortable();
		$fields[] = Number::make('Width (mm)', 'width')->sortable();
		$fields[] = Number::make('Height (mm)', 'height')->sortable();

		if (
            ($this->resource->exists && $this->resource->product_type === 'Variable') || 
            ($request->viaResourceId && Product::find($request->viaResourceId) && Product::find($request->viaResourceId)->product_type === 'Variable')
        ) {
			$fields[] = NovaTinyMCE::make('Description')->options([
                'plugins' => 'link lists preview hr anchor pagebreak image wordcount fullscreen directionality paste textpattern code',
                'toolbar' => 'undo redo | styleselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | image | bullist numlist outdent indent | link'
            ]);
			$fields[] = Boolean::make('Visible');
		} 

		$fields[] = DateTime::make('Created At')->format('Do MMM YYYY')->exceptOnForms();
		
        $fields[] = HasMany::make('Subscription', 'subscriptionOptions', SubscriptionOption::class);

        $fields[] = BelongsToManyField::make('Product Addons', 'productAddons', Variant::class)
				//->hideFromIndex()
				->optionsLabel('title')
				->options(\App\Models\Variant::get());

		return $fields;
	}
	
	/**
     * Return the location to redirect the user after creation.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    // public static function redirectAfterCreate(NovaRequest $request, $resource)
    // {
    //     return '/resources/products/'.$resource->product_id;
    // }

    /**
     * Return the location to redirect the user after update.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    // public static function redirectAfterUpdate(NovaRequest $request, $resource)
    // {
    //     return '/resources/products/'.$resource->product_id;
    // }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
