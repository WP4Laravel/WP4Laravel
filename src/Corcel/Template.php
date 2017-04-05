<?php

namespace WP4Laravel\Corcel;

use View;

trait Template
{
    /**
     * Get the path to the blade template based on
     * the selected template in the Wordpress admin
     * @return string
     */

    public function getTemplateAttribute()
    {
        $options = collect([
            $this->post_type.'.show',
            'post.show',
        ]);

        //	Check if the meta data where the
        //	defined template is saved
        //	if not, use the default template
        if ($this->meta->_wp_page_template) {
            $options->prepend($this->post_type.".".$this->meta->_wp_page_template);
        }

        foreach ($options as $item) {
            if (View::exists($item)) {
                return $item;
            }
        }

        return null;
    }
}
