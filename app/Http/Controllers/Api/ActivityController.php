<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CacheContent;
use App\Models\Activity;
use Carbon\Carbon;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param string $language
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(string $language)
    {
        $api_version = config('app.api_version');

        return CacheContent::remember("api.{$api_version}.{$language}.activities", function () use ($language) {
            $activities = Activity::published()
                ->language($language)
                ->hasMeta('date_start', Carbon::now()->format('Ymd'), '<=')
                ->hasMeta('date_end', Carbon::now()->format('Ymd'), '>=')
                ->get()->sortBy(function ($post, $key) {
                    return $post->meta->date_start;
                });

            return $this->resource('Api\ActivityCollection', $activities);
        }, ['activities']);
    }
}
