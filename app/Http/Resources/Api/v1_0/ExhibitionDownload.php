<?php

namespace App\Http\Resources\Api\v1_0;

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

        $data = (new CachePost($this->resource))->forever("tourdownload.{$api_version}", function ($post) use ($request) {
            $attributes = parent::toArray($request);

            $attributes = array_merge($attributes, [
                'zoom_level' => [
                    'min' => intval($this->meta->zoom_level_min),
                    'max' => intval($this->meta->zoom_level_max),
                ],
                'bounding_boxes' => $this->getBoundingBoxes(),
                'download_url' => $this->download_url,
            ]);

            return $attributes;
        });

        return $data;
    }
}
