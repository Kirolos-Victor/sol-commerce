<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsToMany;

use Benjacho\BelongsToManyField\BelongsToManyField;
use Whitecube\NovaFlexibleContent\Flexible;
use ClassicO\NovaMediaLibrary\MediaLibrary;
use Emilianotisato\NovaTinyMCE\NovaTinyMCE;


class Article extends Resource
{	
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Article::class;

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
        'id', 'title'
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

			Text::make('Url'),

			MediaLibrary::make('Image')->preview('thumb'),

            Textarea::make('Excerpt'),

            NovaTinyMCE::make('Content')->options([
                'plugins' => 'link lists preview hr anchor pagebreak image wordcount fullscreen directionality paste textpattern code',
                'toolbar' => 'undo redo | styleselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | image | bullist numlist outdent indent | link'
            ]),

			Boolean::make('Featured'),
			Boolean::make('Contain Image'),

            DateTime::make('Published At', 'created_at')->default(\Carbon\Carbon::now())->format('D MMM YYYY'),

            BelongsToManyField::make('Categories', 'categories', BlogCategory::class)
                ->hideFromIndex()
                ->optionsLabel('title')
                ->options(\App\Models\BlogCategory::orderBy('title')->get()),


            BelongsToManyField::make('Products', 'products', Product::class)
                ->hideFromIndex()
                ->optionsLabel('title')
                ->options(\App\Models\Product::orderBy('title')->get()),

			BelongsToMany::make('Categories', 'categories', BlogCategory::class),
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
