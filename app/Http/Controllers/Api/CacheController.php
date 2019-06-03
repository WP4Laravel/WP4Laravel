<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;

class CacheController extends Controller
{
    /**
     * All request come into the invoke method and returns all categories
     *
     * @return Response
     */
    public function __invoke()
    {
        \Debugbar::disable();

        //Clearing all cache
        Cache::flush();

        return 'The cache is cleared';
    }
}
