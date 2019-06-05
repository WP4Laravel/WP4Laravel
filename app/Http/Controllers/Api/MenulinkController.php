<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CacheContent;
use App\Models\Menulink;

class MenulinkController extends Controller
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

        return CacheContent::remember("api.{$api_version}.{$language}.menulinks", function () use ($language) {
            return $this->resource('Api\MenulinkCollection', Menulink::published()->language($language)->orderby('menu_order')->get());
        }, ['menulinks']);
    }
}
