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
        return parent::toArray($request);
    }

    // public function toArray($request)
    // {
    //     $route = Route::currentRouteName();

    //     $data = (new CachePost($this->resource))->forever("tour.{$route}", function ($post) use ($request, $route) {
    //         $attributes = parent::toArray($request);

    //         $attributes = array_merge($attributes, [
    //             'last_modified_date' => \OutputHelper::formatDate($this->post_modified),
    //             'categories' => $this->categories()->get()->map(function ($category) {
    //                 return [
    //                     'name' => $category->term->name,
    //                     'slug' => $category->term->slug,
    //                 ];
    //             }),
    //             'subtitle' => ($this->meta->subtitle) ? $this->meta->subtitle : null,
    //             'download_size' => ($this->meta->zip_size) ? $this->meta->zip_size : '',
    //             'distance' => $this->meta->distance,
    //             'duration' => $this->meta->duration,
    //         ]);

    //         if (is_null($route) || $route === 'tour_show' || $route === 'tour_download') {
    //             $attributes = array_merge($attributes, [
    //                 'zoom_level' => [
    //                     'min' => intval($this->meta->zoom_level_min),
    //                     'max' => intval($this->meta->zoom_level_max),
    //                 ],
    //                 'bounding_boxes' => $this->getBoundingBoxes(),

    //                 'header_note' => $this->formatOutput('header_note'),
    //                 'route' => json_decode($this->meta->geolocation_list),

    //                 'widgets' => $this->widgets(),

    //                 'tourhighlights' => $this->tourhighlights(),
    //                 'hotspots' => $this->hotspots()
    //             ]);
    //         }

    //         return $attributes;
    //     });

    //     return $data;
    // }
}
