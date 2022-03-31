<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Select;
use Whitecube\NovaFlexibleContent\Flexible;

use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
use ClassicO\NovaMediaLibrary\MediaLibrary;

class Page extends Resource
{	
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Page::class;

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

			Text::make('Url')->sortable(),

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
                    Text::make('Button Text'),
                    
                    Boolean::make('Centered'),
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
                        Select::make('Item')->options(\App\Models\ProductItem::pluck('title', 'id'))
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
                ])
                ->addLayout('Contact Form', 'contact_form', [
                    
                ])
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
