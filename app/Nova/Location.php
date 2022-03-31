<?php

namespace App\Nova;

use Illuminate\Http\Request;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Place;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsToMany;

use Laravel\Nova\Panel;

use Laravel\Nova\Http\Requests\NovaRequest;

class Location extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Location::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'trading_name', 'email',
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
			ID::make()->sortable(),

			Text::make('Name')->sortable()->rules('required'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email')
                ->creationRules('unique:locations,email')
                ->updateRules('unique:locations,email,{{resourceId}}'),

			Text::make('Phone'),

			Textarea::make('Postcodes'),

			Text::make('Stripe PK'),
			Text::make('Stripe SK'),

			new Panel('Address', [
				Place::make('Address')->hideFromIndex()->countries(['AU'])
					->city('city')
					->state('state')
					->postalCode('postcode')
					->country('country')
					->latitude('latitude')
					->longitude('longitude'),

				Text::make('Apartment')->hideFromIndex(),
				Text::make('City', 'city')->hideFromIndex(),
				Text::make('State', 'state')->hideFromIndex(),
				Text::make('Post Code', 'postcode')->hideFromIndex(),
				Hidden::make('Country', 'country')->hideFromIndex(),
			]),

			BelongsToMany::make('Products'),
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
