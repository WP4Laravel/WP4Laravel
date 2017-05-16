<?php

namespace WP4Laravel;

use Corcel\Menu as CorcelMenu;
use Corcel\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

/**
 * Utility class for determining menu contents
 */
class MenuBuilder
{
    /**
     * Current request to the application
     * @var Illuminate\Http\Request
     */
    private $request;

    private $menu;

    /**
     * Construct the utility class
     * @param string       $slug    slug of the menu
     * @param Request|null $request current request to highlight items
     */
    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * Iterate over all navigation items to return them in a nested collection
     * use $item->children for the subcollection. This method supports a single
     * level of nesting.
     * @return Collection       StdClass objects with id, title, active and url
     *                                   properties. Includes a children
     *                                   collection (one level).
     */
    public function itemsIn(CorcelMenu $menu) : Collection
    {
        if (!$menu) {
            throw new InvalidArgumentException('Invalid or non-existent menu');
        }
        $this->menu = $menu;

        $rootItems = $this->menu->nav_items->filter(function ($item) {
            return $item->meta->_menu_item_menu_item_parent == '0';
        });

        foreach ($rootItems as $root) {
            $root->children = collect();
            foreach ($this->childrenOf($root) as $child) {
                $root->children->push($this->format($child));
            }

            $root->childActive = ($root->children->filter->active->count() > 0);
        }

        return $rootItems;
    }

    /**
     * Find all children of an item, or the root-level items when parent is null
     * @param  Collection $items    Menu items
     * @param  mixed        $parentID id of the parent, optional
     * @return Collection
     */
    private function childrenOf(Post $item) : Collection
    {
        return $this->menu->nav_items->filter(function ($candidate) use ($item) {
            return $candidate->meta->_menu_item_menu_item_parent == $item->ID;
        });
    }

    /**
     * Restructures a nav_menu_item into a useful format
     * @param  Post     $item a post object of type nav_menu_item
     * @param  string   $url  optional URL to highlight the current page
     * @return StdClass       having properties id, title, active && url
     */
    private function format(Post $item)
    {
        $result = new \StdClass;
        $result->id = $item->ID;

        // Use this item's URL, or fallback to the post URL
        $result->url = $item->meta->_menu_item_url;
        if (empty($result->url)) {
            $post = Post::find($item->meta->_menu_item_object_id);
            if (!$post) {
                throw new Exception('Got menu item that is neither a post nor custom URL');
            }
            $result->url = $post->slug;
        }

        // Add a leading slash if required
        if ($result->url !== '#' && substr($result->url, 0, 1) !== '/') {
            $result->url = '/' . $result->url;
        }

        // Set active flag if this is the current page
        $result->active = ($this->request && '/'.$this->request->path() === $result->url);

        // Use this link's title, or fallback to the post title
        $result->title = $item->post_title;
        if (empty($result->title)) {
            $post = Post::find($item->meta->_menu_item_object_id);
            if ($post) {
                $result->title = $post->title;
            } else {
                $result->title = '';
            }
        }

        return $result;
    }
}
