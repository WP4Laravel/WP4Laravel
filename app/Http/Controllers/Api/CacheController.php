<?php

namespace App\Http\Controllers\Api;

use Debugbar;
use App\Http\Controllers\Controller;
use App\Services\CacheContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * All request come into the invoke method and returns all categories
     *
     * @return Response
     */
    public function __invoke(string $tag = null)
    {
        // Temp disable debugbar
        Debugbar::disable();

        if ($this->request->get('action') === 'clear_cache') {
            if ($tag) {
                // Clearing cache for given tag
                CacheContent::flush([$tag]);
                return "The cache is cleared for tag {$tag}";
            } else {
                // Clearing all cache
                Cache::flush();
                return 'The cache is cleared for all content';
            }
        }

        return 'No cache changes';
    }
}
