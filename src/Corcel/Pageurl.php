<?php

namespace WP4Laravel\Corcel;

use Corcel\Model\Option;

trait Pageurl
{

    /**
     * Get the current page based on a
     * hierachel structure based on the url
     * @param   string $url
     * @param   boolean $abort
     * @return Page | null
     */
    public static function url($url, $abort = true)
    {
        // Explode the url by slashes and reverse the collection
        $segments = collect(explode("/", $url))->reverse();

        //  Remove the first part from the url segments
        //  This will be the page to search as a starting point
        $first = $segments->shift();

        // Get all pages with the slug based on the last segment
        $pages = static::slug($first)->published()->get();

        //  Filter al found pages with the same slug
        $selected = $pages->filter(function ($page) use ($segments) {

            //  The current page has no parent
            //  and there are not anymore segments
            //  So the requested page, has to be this one
            if (!$page->post_parent && !$segments->count()) {
                return true;
            }

            //  The current page has a parent,
            //  but there not anymore segments
            //  So it cannot be this page
            if ($page->post_parent && !$segments->count()) {
                return false;
            }

            //  Temp var to hold the current page in the hierarchy
            $current = $page;

            //  Loop through the remaining url segments
            foreach ($segments as $segment) {
                //  Set the current to the parent of the page
                $current = $current->parent;

                //  Check if de slug of the parent
                //  is the same as the current segment
                //  if not return false
                if (!$current || $current->slug != $segment) {
                    return false;
                }
            }

            // Current will be not null if all segments
            // are looped succesfully be finding a page with a
            // slug corresponding with each segment.
            if ($current) {
                return true;
            }

            //  No page foun
            return false;
        });
        //  Return the first item of the filtered collection
        if ($return = $selected->first()) {
            return $return;
        }

        //  If abort is true, send a 404
        if ($abort) {
            abort(404);
        }

        //  Otherwise just return null
        return null;
    }

    /**
     * Get the URL of a Page, by checking the path of parents
     * @return string
     */
    public function getUrlAttribute()
    {
        $parts = [$this->slug];

        $parent = $this->parent;
        while ($parent) {
            array_unshift($parts, $parent->slug);
            $parent = $parent->parent;
        }

        return '/' . implode('/', $parts);
    }


    /**
     * Get the setted homepage from the WP options table
     * @return Page | null
     */
    public static function homepage()
    {
        //  Get the setting for the setted homepage
        if (!$id = Option::get('page_on_front')) {
            return null;
        }

        //  Find the page based on the id from the setting
        if (!$page = static::find($id)) {
            return null;
        }

        return $page;
    }
}
