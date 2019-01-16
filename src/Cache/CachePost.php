<?php

namespace WP4Laravel\Cache;

use Carbon\Carbon;
use Closure;
use Corcel\Model;
use Illuminate\Support\Facades\Cache;

/**
 * This class adds a cache context for WP posts
 * Only return the cache if key not exists or the updated time of the post is recent
 *
 * Usage: $test = (new PostCache($post))->remember("my_key", function() { return "my original data"; });
**/
class CachePost
{
    protected $post;
    private $tag = 'content';

    /**
     * Initilize the object with the current post
     * @param Model $post
     */
    public function __construct(Model $post)
    {
        $this->post = $post;
    }

    /**
     * Handle the request to get the $key from the cache or parse the default
     * @param  string  $key
     * @param  Closure $default
     * @return mixed
     */
    public function forever($key, Closure $default)
    {
        // Check if cache key exists
        // If not save it to the cache and return the callback
        if (!Cache::has($this->getCacheKey($key))) {
            return $this->save($key, $default);
        }

        // Check if cache key timestamp exists
        // If not save it to the cache and return the callback
        if (!$timestamp = $this->getTimestamp($key)) {
            return $this->save($key, $default);
        }

        // Check if the post_modified attribute of the post is a Carbon object
        // If not create new entry
        if (!$this->post->post_modified instanceof Carbon) {
            return $this->save($key, $default);
        }

        // Check if cache timestamp is newer dan the modified time of the post
        // If not, forget the current cache, save it to the cache and return the callback
        if ($this->post->post_modified->gt($timestamp)) {
            return $this->save($key, $default);
        }

        // Return from cache!
        return Cache::get($this->getCacheKey($key));
    }

    /**
     * Save the $key to the cache
     * @param  string  $key
     * @param  Closure $default
     * @return mixed
     */
    protected function save($key, Closure $default)
    {
        // Get the cache keys for the main results
        // and one for the timestamp
        $cache_key = $this->getCacheKey($key);
        $cache_key_timestamp = $cache_key . "_timestamp";

        // If cache exists, first forget the old data
        // TODO: Is this nescessary when saving data under the same key?
        Cache::forget($cache_key);
        Cache::forget($cache_key_timestamp);

        // Run the original logic from the callback
        $result = $default($this->post);

        // Save the cache forever
        if ($this->hasTags()) {
            Cache::tags($this->tag)->forever($cache_key, $result);
            Cache::tags($this->tag)->forever($cache_key_timestamp, Carbon::now());
        } else {
            Cache::forever($cache_key, $result);
            Cache::forever($cache_key_timestamp, Carbon::now());
        }

        // Return the default result
        return $result;
    }

    /**
     * Get the cache key based on the post data and the given subkey
     * @param  string $subkey
     * @return string
     */
    protected function getCacheKey($subkey)
    {
        $prefix = config('database.connections.wordpress.prefix');
        $type = $this->post->post_type;
        $id = $this->post->ID;

        return implode('', [$prefix, $type, '_', $id, '_', $subkey]);
    }

    /**
     * Get the cache key of the the timestamp
     * @param  string $subkey
     * @return string
     */
    protected function getTimestampCacheKey($subkey)
    {
        // It's the same as the normal cache key
        // but suffixed with _timestamp
        return $this->getCacheKey($subkey) . '_timestamp';
    }

    /**
     * Get the timestamp cache item
     * @param  string $subkey [description]
     * @return Carbon | null
     */
    protected function getTimestamp($subkey)
    {
        // Get the cache key for the timestamp
        $cache_key = $this->getTimestampCacheKey($subkey);

        // Does the timestamp exists in cache?
        // If not return null, so the data will not returned from cache
        if (!Cache::has($cache_key)) {
            return null;
        }

        // Get the cached timestamp
        $timestamp = Cache::get($cache_key);

        // Check if the cached timestamp is an instance of Carbon
        if ($timestamp instanceof Carbon) {
            return $timestamp;
        }

        // If not return null, so the data will not returned from cache
        return null;
    }

    private function hasTags()
    {
        return Cache::getStore() instanceof \Illuminate\Cache\TaggableStore;
    }
}
