<?php

namespace App\Services;

use Cache;
use Closure;

class CacheContent
{
    /**
     * Default minutes for caching
     *
     * @var integer
     */
    protected $limit = 1440;

    /**
     * Get an item from the cache, or execute the given Closure and store the result.
     *
     * @param string $name
     * @param Closure $callback
     * @param array $tags
     * @return mixed
     */
    public static function remember(string $name, Closure $callback, array $tags = null)
    {
        $self = new static();

        if ($self->supportTags() && !is_null($tags)) {
            return Cache::tags($tags)->remember($name, $self->limit, $callback);
        }

        return Cache::remember($name, $self->limit, $callback);
    }

    /**
     * Check if cache tags are supported.
     *
     * @return bool
     */
    protected function supportTags()
    {
        return Cache::getStore() instanceof \Illuminate\Cache\TaggableStore;
    }
}
