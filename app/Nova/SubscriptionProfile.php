<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Boolean;

class SubscriptionProfile extends Resource
{	
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\SubscriptionProfile::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'variant.title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id'
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
			ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make('User'),
            BelongsTo::make('Variant'),
            BelongsTo::make('Subscription Option', 'subscriptionOption'),

			Number::make('Delivery Frequency (weeks)', 'frequency'),
			Number::make('Price'),
			Number::make('Qty'),
			Date::make('Next Order'),

			Number::make('Buffer Days'),
			Number::make('Pause Count'),
			Number::make('Pause Days'),

			Number::make('Paused Count'),
			Date::make('Paused Until'),

			Boolean::make('Cancelled', 'cancelled_at')->exceptOnForms()
		];
	}

	// public static function redirectAfterCreate(NovaRequest $request, $resource)
    // {
    //     return '/resources/products/'.$resource->id.'/edit';
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
        return [
			new \App\Nova\Actions\CancelSubscription
        ];
    }
}
