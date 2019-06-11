<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exhibition;
use App\Services\ZipResource;
use App\Services\CacheContent;

class ExhibitionController extends Controller
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

        return CacheContent::remember("api.{$api_version}.{$language}.exhibitions", function () use ($language) {
            return $this->resource('Api\ExhibitionCollection', Exhibition::published()->language($language)->orderby('post_title')->get());
        }, ['exhibition']);
    }

    /**
     * Show a tour resource by the given language and id
     *
     * @param string $language
     * @param int $id
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function show(string $language, int $id)
    {
        $exhibition = Exhibition::published()->language($language)->findOrFail($id);

        return $this->resource('Api\Exhibition', $exhibition);
    }

    /**
     * Download a Zip archive with the Tour json and all media files
     *
     * @param string $language
     * @param int $id
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function download(string $language, int $id)
    {
        ini_set('memory_limit', '512M');

        $exhibition = Exhibition::published()->language($language)->findOrFail($id);

        // zip size is set on save_post
        if (!$exhibition->meta->zip_size) {
            // Update dates for correct caching
            $exhibition->post_modified = \Carbon\Carbon::now();
            $exhibition->post_modified_gmt = \Carbon\Carbon::now('UTC');
            $exhibition->save();
        }

        $data = $this->resource('Api\Exhibition', $exhibition);

        $zipper = new ZipResource($data);

        if (!$zipper->url()) {
            // Zip does not exist, creating one
            $zipper->zip();

            // Add zip size to database as meta value zip_size
            $exhibition->saveMeta('zip_size', $zipper->tempArchiveSize());

            // Save newly created zip
            $zipper->save();
        }

        $exhibition->download_url = $zipper->url();

        return $this->resource('Api\ExhibitionDownload', $exhibition);
    }
}
