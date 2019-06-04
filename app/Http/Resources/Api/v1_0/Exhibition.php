<?php

namespace App\Http\Resources\Api\v1_0;

use Route;
use WP4Laravel\Cache\CachePost;

class Exhibition extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $api_version = config('app.api_version');

        $route = Route::currentRouteName();

        $data = (new CachePost($this->resource))->forever("api.{$api_version}.exhibitions.{$route}", function ($post) use ($request, $route) {
            $attributes = parent::toArray($request);
            if (is_null($route) || $route === 'exhibition_show' || $route === 'exhibition_download') {
                return array_merge($attributes, [
                    'artists' => $this->artists(),
                    'artworks' => $this->artworks()
                ]);
            }
            return $attributes;
        });

        return $data;
    }
}
