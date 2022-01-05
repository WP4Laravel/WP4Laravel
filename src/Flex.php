<?php

namespace WP4Laravel;

use Corcel\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

/**
 * Render an ACF - Flexible Content  attribute from a post
 * A Flexible content field contains components which are defined with a reference
 * The convention is that every component has a matching template partials, based on the reference of the component
 * This library will search for the template and render it all
 */
class Flex
{
    /**
     * The current post
     * @var \Corcel\Model
     */
    protected $post;

    /**
     * The ACF Field
     * @var \Illuminate\Support\Collection
     */
    protected $field;

    /**
     * Initializing the Flex library
     * @param Model   $post
     * @param string $field
     */
    public function __construct(Model $post, $field)
    {
        //  Save the post as an object variable
        $this->post = $post;

        //  Get the Flexible Content collection from the post
        $this->field = $this->getFieldData($field);
    }

    protected function getFieldData($field)
    {
        return Cache::rememberForever($this->post->ID."_flex_{$field}", function () use ($field) {
            return $this->post->acf->flexible_content($field);
        });

    }

    /**
     * Check all components
     */
    public function content() : Collection
    {
        //  Does the field exists
        if (empty($this->field)) {
            return collect();
        }

        //  Create per component a path to the view
        return $this->field->map(function ($item) {
            $item->view = "flex." . $item->type;

            return $item;

            //  Filter out items, which have not a matching template partial
        })->filter(function ($item) {
            return View::exists($item->view);
        });
    }

    /**
     * Render all components and return as HTML
     */
    public function render() : string
    {
        if ($this->content()->isEmpty()) {
            return '';
        }

        //  Map over every item and reduce the result to a HTML
        return $this->content()->reduce(function ($container, $item) {
            $view = View::make($item->view, ['fields' => $item->fields, 'post' => $this->post]);

            //  Append the new result to the result of all previous items
            return $container . $view->render();
        });
    }


    public function text() : Collection
    {
        return $this->content()->map(function ($item) {
            return collect($item->fields)->reduce(function ($container, $sub) {
                if (is_string($sub) && !empty($sub)) {
                    return $container . PHP_EOL . $sub;
                }
            });
        });
    }
}
