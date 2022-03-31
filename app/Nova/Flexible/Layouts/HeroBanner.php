<?php

namespace App\Nova\Flexible\Layouts;

use Whitecube\NovaFlexibleContent\Layouts\Layout;

use Whitecube\NovaFlexibleContent\Flexible;

use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

use Laravel\Nova\Fields\Text;
use ClassicO\NovaMediaLibrary\MediaLibrary;

use ClassicO\NovaMediaLibrary\API;

class HeroBanner extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'hero_banner';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Hero Banner';

    /**
     * Get the fields displayed by the layout.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Flexible::make('Hero Banner')->addLayout('Banner', 'banner', [
                Flexible::make('Banners')->addLayout(\App\Nova\Flexible\Layouts\Banner::class)
            ])
        ];
    }

    public function getBannerAttribute() {
        return $this->flexible('hero_banner', [
			'banner' => \App\Nova\Flexible\Layouts\Banner::class
		]);
    }

    // public function getImageObjectAttribute($value) {
	// 	if ($this->image) {
	// 		return API::getFiles($this->image, null, true);
	// 	}
	// }
}
