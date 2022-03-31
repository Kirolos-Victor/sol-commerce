<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;

use Laravel\Nova\Panel;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Place;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Image;

// use Nims\OrderItems\OrderItems;

use Illuminate\Support\Str;

class Order extends Resource
{	
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order::class;

	public static $with = ['location', 'user', 'orderItems'];

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
			ID::make(__('ID'), 'id')->sortable(),

			BelongsTo::make('Location')->sortable()->required(),
			BelongsTo::make('Customer', 'user', User::class)->nullable()->sortable(),
			BelongsTo::make('Subscription Profile', 'subscriptionProfile', SubscriptionProfile::class)->nullable(),
		
			Select::make('Status')
				->onlyOnForms()
				->options(config('app.order_statuses'))
				->required(),

			Text::make('Status', function () {
				return '<div class="status-pill status-'.Str::slug($this->status).'">'.$this->status.'</div>';
			})->exceptOnForms()->asHtml(),

			Text::make('Email'),
			Text::make('First Name'),
			Text::make('Last Name'),
			Text::make('Phone'),

			Date::make('Delivery Date'),

			new Panel('Totals', [
				Currency::make('Subtotal')->onlyOnDetail(),
				Currency::make('Tax')->onlyOnDetail(),
				Currency::make('Shipping', 'shipping_price')->onlyOnDetail(),
				Currency::make('Discount Amount', 'discount_amount')->onlyOnDetail(),
				Currency::make('Total')->exceptOnForms(),
			]),	

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

            Currency::make('Shipping Price')->nullable(),

			BelongsTo::make('Discount Code', 'discountCode', DiscountCode::class)->nullable()->hideFromIndex(),
            Currency::make('Discount Amount')->nullable()->hideFromIndex(),
		
            Currency::make('Refund Amount')->nullable()->hideFromIndex()->exceptOnForms(),
            Textarea::make('Refund Reason')->nullable()->hideFromIndex()->exceptOnForms(),

			Textarea::make('Notes'),

			DateTime::make('Created At')->format('Do MMM YYYY')->exceptOnForms(),

			new Panel('Order Items', [
				// OrderItems::make('Order Items')
				// 	->stacked()
				// 	->onlyOnForms()
			]),	

			HasMany::make('Order Items', 'orderItems', OrderItem::class)->stacked(),
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
        return [
			new Filters\OrderStatus,
		];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [

		];
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
			new \App\Nova\Actions\RefundOrder,
			new \App\Nova\Actions\CancelOrder
		];
	}
	
}
