<?php

namespace App\Http\Resources\Api\v1_0;

use Route;
use WP4Laravel\Cache\CachePost;

class Menulink extends BaseResource
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

        $data = (new CachePost($this->resource))->forever("menulinks.{$api_version}.{$route}", function ($post) use ($request) {
            return parent::toArray($request);
        });

        return $data;
    }
}
