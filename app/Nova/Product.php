<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsToMany;

use ClassicO\NovaMediaLibrary\MediaLibrary;
use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
use Benjacho\BelongsToManyField\BelongsToManyField;
use Whitecube\NovaFlexibleContent\Flexible;

use OptimistDigital\NovaSortable\Traits\HasSortableRows;

class Product extends Resource
{
    use HasSortableRows;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Product::class;

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

			Text::make('Url'),

			Text::make('Subtitle')->hideFromIndex(),

			NovaTinyMCE::make('Description')->options([
                'plugins' => 'link lists preview hr anchor pagebreak image wordcount fullscreen directionality paste textpattern code',
                'toolbar' => 'undo redo | styleselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | image | bullist numlist outdent indent | link'
            ]),

            NovaTinyMCE::make('Benefits')->options([
                'plugins' => 'link lists preview hr anchor pagebreak image wordcount fullscreen directionality paste textpattern code',
                'toolbar' => 'undo redo | styleselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | image | bullist numlist outdent indent | link'
            ]),

			MediaLibrary::make('Image')->preview('thumb'),
            MediaLibrary::make('Hover Image','hover_image')->preview('thumb'),

            MediaLibrary::make('Gallery Images', 'images')
                ->preview('thumb')
                ->array(),

			// Images::make('Gallery Images', 'images')->enableExistingMedia(),
            //     //->conversionOnIndexView('thumb')
            //     // ->setFileName(function($originalFilename, $extension, $model){
            //     //     return md5($originalFilename) . '.' . $extension;
            //     // }),

			Select::make('Product Type')->options([
				'Simple' => 'Simple',
				'Variable' => 'Variable',
			]),

			Text::make('Option Name')->sortable()
				->if(['product_type'], fn($value) => $value['product_type'] !== 'Simple'),

			Boolean::make('Visible'),
			Boolean::make('Perishable'),
			Boolean::make('Enquire Only'),

			Boolean::make('Custom Options'),
			Number::make('Custom Options Choices'),
            BelongsToManyField::make('Custom Product Options', 'productItems', ProductItem::class)
                ->hideFromIndex()
                ->optionsLabel('title')
                ->options(\App\Models\ProductItem::orderBy('title')->get()),

			Date::make('Start Date'),
			Date::make('End Date'),

			Text::make('Product Recommendations Heading'),
            BelongsToManyField::make('Product Recommendations', 'productRecommendations', Product::class)
                ->optionsLabel('title')
                ->options(\App\Models\Product::get()),

            BelongsToManyField::make('Locations', 'locations', Location::class)
                ->hideFromIndex()
                ->optionsLabel('name')
                ->options(\App\Models\Location::orderBy('name')->get()),

            BelongsToManyField::make('Categories', 'categories', Category::class)
                ->hideFromIndex()
                ->optionsLabel('title')
                ->options(\App\Models\Category::orderBy('title')->get()),

            Flexible::make('Content')
                ->onlyOnForms()
                ->addLayout('Hero Banner', 'hero_banner', [
                    Flexible::make('Banners')->addLayout('Banner', 'banner', [
			            MediaLibrary::make('Image')->preview('thumb'),

                        Text::make('Heading')
                    ])->resolver(\App\Nova\Flexible\Resolvers\MediaResolver::class)
                ])
                ->addLayout('Standard Content', 'standard_content', [
                    Text::make('Preheading'),
                    Text::make('Heading'),
                    NovaTinyMCE::make('Content')->options([
                        'plugins' => 'link lists preview hr anchor pagebreak image wordcount fullscreen directionality paste textpattern code',
                        'toolbar' => 'undo redo | styleselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | image | bullist numlist outdent indent | link'
                    ]),

                    Text::make('Button Url'),
                    Text::make('Button Text')
                ])
                ->addLayout('Image & Text', 'image_text', [
                    Boolean::make('Image Right'),
                    MediaLibrary::make('Image')->preview('thumb'),

                    Text::make('Preheading'),
                    Text::make('Heading'),
                    NovaTinyMCE::make('Content')->options([
                        'plugins' => 'link lists preview hr anchor pagebreak image wordcount fullscreen directionality paste textpattern code',
                        'toolbar' => 'undo redo | styleselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | image | bullist numlist outdent indent | link'
                    ]),

                    Text::make('Button Url'),
                    Text::make('Button Text')
                ])->resolver(\App\Nova\Flexible\Resolvers\MediaResolver::class)
                ->addLayout('Image & Text Columns', 'image_text_columns', [
                    Boolean::make('Image Right'),
                    MediaLibrary::make('Image')->preview('thumb'),

                    Boolean::make('Centered'),
                    Select::make('Columns Per Row')->options([
                        2 => '2',
                        3 => '3'
                    ]),

                    Flexible::make('Columns')->addLayout('Column', 'column', [
			            MediaLibrary::make('Image')->preview('thumb'),

                        Text::make('Preheading'),
                        Text::make('Heading'),
                        NovaTinyMCE::make('Content')->options([
                            'plugins' => 'link lists preview hr anchor pagebreak image wordcount fullscreen directionality paste textpattern code',
                            'toolbar' => 'undo redo | styleselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | image | bullist numlist outdent indent | link'
                        ]),

                        Text::make('Button Url'),
                        Text::make('Button Text')
                    ])->resolver(\App\Nova\Flexible\Resolvers\MediaResolver::class)
                ])->resolver(\App\Nova\Flexible\Resolvers\MediaResolver::class)
                ->addLayout('Columns', 'columns', [
                    Boolean::make('Centered'),
                    Boolean::make('Carousel'),

                    Select::make('Columns Per Row')->options([
                        3 => '3',
                        4 => '4'
                    ]),

                    Select::make('Image Height')->options([
                        'Natural' => 'Natural',
                        'Portrait' => 'Portrait',
                        'Landscape' => 'Landscape'
                    ]),

                    Flexible::make('Columns')->addLayout('Column', 'column', [
			            MediaLibrary::make('Image')->preview('thumb'),

                        Text::make('Preheading'),
                        Text::make('Heading'),
                        NovaTinyMCE::make('Content')->options([
                            'plugins' => 'link lists preview hr anchor pagebreak image wordcount fullscreen directionality paste textpattern code',
                            'toolbar' => 'undo redo | styleselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | image | bullist numlist outdent indent | link'
                        ]),

                        Text::make('Button Url'),
                        Text::make('Button Text')
                    ])->resolver(\App\Nova\Flexible\Resolvers\MediaResolver::class)
                ])
                ->addLayout('Item Carousel', 'item_carousel', [
                    Text::make('Heading'),

                    Flexible::make('Slides')->addLayout('Slide', 'slide', [
                        Select::make('Item')->options(\App\Models\ProductItem::pluck('title', 'id')),
                        Text::make('Size'),
                    ])
                ])
                ->addLayout('Accordion', 'tabs', [
                    Flexible::make('Tabs')->addLayout('Tab', 'tab', [
                        Text::make('Heading'),
                        NovaTinyMCE::make('Content')->options([
                            'plugins' => 'link lists preview hr anchor pagebreak image wordcount fullscreen directionality paste textpattern code',
                            'toolbar' => 'undo redo | styleselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | image | bullist numlist outdent indent | link'
                        ]),
                    ])
                ]),

            Flexible::make('Faq')
                ->onlyOnForms()
                ->addLayout('Accordion', 'tabs', [
                    Text::make('Heading'),
                    NovaTinyMCE::make('Content')->options([
                        'plugins' => 'link lists preview hr anchor pagebreak image wordcount fullscreen directionality paste textpattern code',
                        'toolbar' => 'undo redo | styleselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | image | bullist numlist outdent indent | link'
                    ]),
                ]),

			DateTime::make('Created At')->format('Do MMM YYYY')->exceptOnForms()->hideFromIndex(),

			HasMany::make('Variants'),

			BelongsToMany::make('Locations'),
			BelongsToMany::make('Categories'),
			BelongsToMany::make('Reviews')
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
