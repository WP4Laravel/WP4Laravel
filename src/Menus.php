<?php

namespace WP4Laravel;

use Corcel\Menu;
use Illuminate\Support\Collection;

/**
 * Model for Wordpress menu's
 */
class Menus
{

    /**
     * Render menu items as array based on the menu slug
     * @param  string $slug
     * @return Collection
     */
    public static function all()
    {
        $menus = Menu::all();
        $return = [];
        $self = new static();

        $menus->each(function ($item) use (&$return, $self) {
            $return[$item->slug] = $self->render($item);
        });

        return $return;
    }

    public static function item($slug)
    {
        //	Get the menu by slug
        //	If not found, return an empty collection
        //	This prevents exceptions on your frontend
        if (!$menu = Menu::slug($slug)->first()) {
            return collect();
        }

        $self = new static();

        return $self->render($menu);
    }

    /**
     * Render a menu object as menu items
     * @return Collection
     */
    public function render($menu)
    {
        //	Map through eacht menu item
        //	and return an array of title and url
        $result = $menu->nav_items->map(function ($item) {
            return collect(['title'=>$item->title, 'url'=> $item->meta->_menu_item_url]);
        });

        //	Return the collection
        return $result;
    }
}
