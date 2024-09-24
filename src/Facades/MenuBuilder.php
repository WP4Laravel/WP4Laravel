<?php

namespace WP4Laravel\Facades;

use Corcel\Model\Menu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 *
 * @method static Collection all()
 * @method static Collection itemsIn(Menu $menu)
 * @method static Menu|null menuForLocation(string $location, string|null $language = null)
 *
 * @see MenuBuilder
 */
class MenuBuilder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'wp4laravel::menubuilder';
    }
}
