<?php

namespace App\Nova\Flexible\Layouts;

use Whitecube\NovaFlexibleContent\Layouts\Layout;

use Laravel\Nova\Fields\Text;
use ClassicO\NovaMediaLibrary\MediaLibrary;

use ClassicO\NovaMediaLibrary\API;

class Banner extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'banner';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Banner';

    protected $appends = ['image_object'];

    /**
     * Get the fields displayed by the layout.
     *
     * @return array
     */
    public function fields()
    {
        return [
            MediaLibrary::make('Image')->preview('thumb'),
            Text::make('Heading')
        ];
    }

    public function getImageObjectAttribute($value) {
		if ($this->image) {
			return API::getFiles($this->image, null, true);
		}
	}
}