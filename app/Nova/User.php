<?php

namespace App\Nova;

use Illuminate\Http\Request;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Place;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsToMany;

use Laravel\Nova\Panel;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    //public static $title = 'first_name';

	function title() {
		return $this->first_name. ' '.$this->last_name;
	}

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'first_name', 'email',
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

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Text::make('First Name')
                ->sortable()
                ->rules('required', 'max:255'),

			Text::make('Last Name')
                ->sortable()
                ->rules('required', 'max:255'),

			Text::make('Phone'),

            new Panel('Shipping Address', [
				Place::make('Address', 'shipping_address')->hideFromIndex()->countries(['AU'])
					->city('shipping_city')
					->state('shipping_state')
					->postalCode('shipping_postcode')
					->country('shipping_country'),

				Text::make('Apartment', 'shipping_apartment')->hideFromIndex(),
				Text::make('City', 'shipping_city')->hideFromIndex(),
				Text::make('State', 'shipping_state')->hideFromIndex(),
				Text::make('Postcode', 'shipping_postcode')->hideFromIndex(),
				Text::make('Country', 'shipping_country')->hideFromIndex(),
			]),

			Textarea::make('Notes'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),

            Boolean::make('Local Pickup'),
            Boolean::make('Admin'),

			HasMany::make('Orders')
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
