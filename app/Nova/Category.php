<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsToMany;

use Laravel\Nova\Http\Requests\NovaRequest;
use ClassicO\NovaMediaLibrary\MediaLibrary;

use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
use Whitecube\NovaFlexibleContent\Flexible;

use Benjacho\BelongsToManyField\BelongsToManyField;

class Category extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Category::class;

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
			ID::make(__('ID'), 'id')->sortable(),
			
			BelongsTo::make('Parent Category', 'parent', Category::class)->nullable(),

			Text::make('Title')->sortable(),
			Text::make('Url'),
			Textarea::make('Short Description'),
			Textarea::make('Description'),
			MediaLibrary::make('Image')->preview('thumb'),

			Textarea::make('Footer Quote'),

            Flexible::make('Faq')
                ->onlyOnForms()
                ->addLayout('Accordion', 'tabs', [
                    Text::make('Heading'),
                    NovaTinyMCE::make('Content')->options([
                        'plugins' => 'link lists preview hr anchor pagebreak image wordcount fullscreen directionality paste textpattern code',
                        'toolbar' => 'undo redo | styleselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | image | bullist numlist outdent indent | link'
                    ]),
                ]),

			HasMany::make('Subcategories', 'subcategories', Category::class),

			BelongsToMany::make('Products'),

            BelongsToManyField::make('Product Information', 'productInformation', ProductInformation::class)
                ->optionsLabel('title')
                ->options(\App\Models\ProductInformation::get()),
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
        return [

		];
    }
}
