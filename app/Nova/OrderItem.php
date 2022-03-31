<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\BelongsTo;

class OrderItem extends Resource
{
	public static $displayInNavigation = false;
	
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\OrderItem::class;

	public static $with = ['variant'];

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
			//ID::make(__('ID'), 'id')->sortable(),
		
			BelongsTo::make('Order'),
			BelongsTo::make('Product', 'variant', Variant::class)->searchable(),
			BelongsTo::make('Subscription Option', 'subscriptionOption', SubscriptionOption::class)->nullable(),

			Currency::make('Price')->step(0.01),
			Number::make('Qty'),

			Currency::make('Tax')->step(0.01),
			Currency::make('Subtotal')->step(0.01),
			Currency::make('Total')->step(0.01),

			KeyValue::make('Custom Options')->rules('json'),
        ];
	}

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
