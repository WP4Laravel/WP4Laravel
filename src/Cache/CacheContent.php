<?php

namespace WP4Laravel\Cache;

use Cache;
use Closure;

class CacheContent
{
    protected $limit = 1440;
    protected $tag = 'content';


    public static function remember($name, Closure $closure)
    {
        return (new static())->put($name, $closure);
    }

    public static function flush()
    {
        $self = new static();

        if ($self->hasTags()) {
            return Cache::tags($self->tag)->flush();
        }

        return Cache::flush();
    }

    public function put($name, Closure $closure)
    {
        if ($this->hasTags()) {
            return Cache::tags($this->tag)->remember($name, $this->limit, $closure);
        }

        return Cache::remember($name, $this->limit, $closure);
    }

    protected function hasTags()
    {
        return Cache::getStore() instanceof \Illuminate\Cache\TaggableStore;
    }
}
