<?php

namespace App\Http\Resources\Api\v1_0;

use Route;
use WP4Laravel\Cache\CachePost;

class ExhibitionDownload extends BaseResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $api_version = config('app.api_version');

        $route = Route::currentRouteName();

        $data = (new CachePost($this->resource))->forever("api.{$api_version}.exhibitiondownloads.{$route}", function ($post) use ($request) {
            $attributes = parent::toArray($request);

            $attributes = array_merge($attributes, [
                'download_url' => $this->download_url,
            ]);

            return $attributes;
        });

        return $data;
    }
}
