<?php

namespace WP4Laravel\Cache;

use Closure;
use Illuminate\Support\Facades\Cache;

class CacheContent
{
    protected $limit = 1440;
    protected $tag = 'content';


    protected $times = [
        'everyMinute'   =>  1,
        'everyFiveMinutes' => 5,
        'everyFifteenMinutes'   =>  15,
        'hourly'    =>  60,
        'twiceADay' =>  720,
        'daily' =>  1440,
        'weekly' => 1440 * 7,
        'monthly' => 1440 * 30
    ];

    public static function __callStatic($method, $args)
    {
        $self = new static();

        if (array_key_exists($method, $self->times) !== false) {
            $self->limit = $self->times[$method];

            return $self->put($args[0], $args[1]);
        }
    }

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
