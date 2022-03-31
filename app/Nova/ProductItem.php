<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;

use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
use ClassicO\NovaMediaLibrary\MediaLibrary;
use OptimistDigital\NovaTableField\Table;

class ProductItem extends Resource
{	
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ProductItem::class;

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
        'id', 'title', 'subtitle'
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

			Text::make('Title')->detailLink()->sortable(),

			Text::make('Subtitle')->hideFromIndex(),

			NovaTinyMCE::make('Content')->options([
                'plugins' => 'link lists preview hr anchor pagebreak image wordcount fullscreen directionality paste textpattern code',
                'toolbar' => 'undo redo | styleselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | image | bullist numlist outdent indent | link'
            ]),

			MediaLibrary::make('Image')->preview('thumb'),

            Table::make('Nutritional Info'),
			// MediaLibrary::make('Nutritional Info')->preview('thumb'),
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
        return [];
    }
}
