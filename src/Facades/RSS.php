<?php

namespace WP4Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class RSS extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'wp4laravel::rss';
    }
}
