<?php

namespace WP4Laravel;

use Corcel\Model\Menu as CorcelMenu;
use Corcel\Model\Post;
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
     * Returns a keyed set of all menu's
     * @return Collection
     */
    public function all() : Collection
    {
        return CorcelMenu::all()->mapWithKeys(function ($menu) {
            return [$menu->slug => $this->itemsIn($menu)];
        });
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

        // Get all menu items and related posts, we are going to need those
        // later (prevents N+1 queries)
        $allItems = $menu->items()->get();
        $allPosts = $this->getPostsCache($allItems);

        $rootItems = $allItems->filter(function ($item) {
            return $item->meta->_menu_item_menu_item_parent == '0';
        })->map(function ($item) use ($allItems, $allPosts) {
            $formatted = $this->format($item, $allPosts);

            $formatted->children = collect();
            foreach ($this->childrenOf($item, $allItems) as $child) {
                $formatted->children->push($this->format($child, $allPosts));
            }

            $formatted->childActive = ($formatted->children->filter->active->count() > 0);

            return $formatted;
        });

        return $rootItems;
    }

    /**
     * Find all children of an item, or the root-level items when parent is null
     * @param  Post       $item     the item of record
     * @param  Collection $allItems all items in the menu
     * @return Collection           all child items from the item of record
     */
    private function childrenOf(Post $item, $allItems) : Collection
    {
        return $allItems->filter(function ($candidate) use ($item) {
            return $candidate->meta->_menu_item_menu_item_parent == $item->ID;
        });
    }

    /**
     * Restructures a nav_menu_item into a useful format
     * @param  Post     $item       a post object of type nav_menu_item
     * @param  Collection $allPosts all posts referred to in the menu
     * @return StdClass             having properties id, title, active && url
     */
    private function format(Post $item, $allPosts)
    {
        $result = new \StdClass;
        $result->id = $item->ID;

        // Use this item's URL, or fallback to the post URL
        $result->url = $item->meta->_menu_item_url;
        if (empty($result->url)) {
            $post = $allPosts[$item->meta->_menu_item_object_id];
            if (!$post) {
                throw new Exception('Got menu item that is neither a post nor custom URL');
            }
            $result->url = !empty($post->url) ? $post->url : $post->slug;
        }

        // Set active flag if this is the current page
        $currentPath = $this->ensureLeadingSlash($this->request->path());
        $menuPath = $this->ensureLeadingSlash($result->url);
        $result->active = ($currentPath === $menuPath);

        // Use this link's title, or fallback to the post title
        $result->title = $item->post_title;
        if (empty($result->title)) {
            $post = $allPosts[$item->meta->_menu_item_object_id];
            if ($post) {
                $result->title = $post->title;
            } else {
                $result->title = '';
            }
        }

        return $result;
    }

    /**
     * Add a leading slash to normalize an URL
     * unless the URL starts with a / or an #
     * @param  string $path valid path segment
     * @return string
     */
    private function ensureLeadingSlash(string $path)
    {
        $firstChar = substr($path, 0, 1);
        if (in_array($firstChar, ['/', '#'])) {
            return $path;
        } else {
            return '/' . $path;
        }
    }

    /**
     * Find all posts that are referred to in the menu
     * @param  Collection $allItems all menu nav_items
     * @return Collection           all posts, indexed by post->ID
     */
    private function getPostsCache($allItems)
    {
        $ids = $allItems->map(function ($item) {
            return $item->meta->_menu_item_object_id;
        });

        return Post::whereIn('id', $ids)->with('meta')->get()->keyBy('ID');
    }
}
