<?php

namespace WP4Laravel\Corcel;

trait TemplateTrait
{
    /**
     * Get the path to the blade template based on
     * the selected template in the Wordpress admin
     * @return string
     */

    public function getTemplateAttribute()
    {
        //	Make a collection with one item,
        //	the current post type
        $set = collect([$this->postType]);

        //	Check if the meta data where the
        //	defined template is saved
        //	if not, use the default template
        if (!$this->meta->_wp_page_template) {
            $set->push("default");
        } else {
            $set->push($this->meta->_wp_page_template);
        }

        //	TODO: Also check if the default exists.

        //	Join the array determined by dots (blade syntax)
        return $set->implode(".");
    }
}
