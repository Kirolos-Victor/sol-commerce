<?php

namespace App\Nova\Flexible\Resolvers;

use Whitecube\NovaFlexibleContent\Value\ResolverInterface;

use ClassicO\NovaMediaLibrary\API;

class MediaResolver implements ResolverInterface
{
    /**
     * Set the field's value
     *
     * @param  mixed  $model
     * @param  string $attribute
     * @param  Illuminate\Support\Collection $groups
     * @return string
     */
    public function set($model, $attribute, $groups)
    {
        return $model->$attribute = $groups->map(function($group) {

            $attributes = $group->getAttributes();
            if (!empty($attributes['image'])) {
                $attributes['image_object'] = API::getFiles($attributes['image'], null, true);
            }

            return [
                'layout' => $group->name(),
                'key' => $group->key(),
                'attributes' => $attributes
            ];
        });
    }

    /**
     * get the field's value
     *
     * @param  mixed  $resource
     * @param  string $attribute
     * @param  Whitecube\NovaFlexibleContent\Layouts\Collection $layouts
     * @return Illuminate\Support\Collection
     */
    public function get($resource, $attribute, $layouts)
    {
        $value = $this->extractValueFromResource($resource, $attribute);

        return collect($value)->map(function($item) use ($layouts) {
            $layout = $layouts->find($item->layout);

            if(!$layout) return;

            return $layout->duplicateAndHydrate($item->key, (array) $item->attributes);
        })->filter()->values();
    }

    /**
     * Find the attribute's value in the given resource
     *
     * @param  mixed  $resource
     * @param  string $attribute
     * @return array
     */
    protected function extractValueFromResource($resource, $attribute)
    {
        $value = data_get($resource, str_replace('->', '.', $attribute)) ?? [];

        if($value instanceof Collection) {
            $value = $value->toArray();
        } else if (is_string($value)) {
            $value = json_decode($value) ?? [];
        }

        // Fail silently in case data is invalid
        if (!is_array($value)) return [];

        return array_map(function($item) {
            return is_array($item) ? (object) $item : $item;
        }, $value);
    }
}
