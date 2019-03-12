<?php

namespace WP4Laravel;

use Corcel\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;

class Gutenberg
{
    /**
     * The parsed data
     * @var
     */
    protected $data;


    public function __construct(array $data)
    {
        $this->data = collect($data);
    }

    /**
     * Check all components
     */
    public function content() : Collection
    {
        //  Create per component a path to the view
        return $this->data->map(function ($item) {
            $item['view'] = "guten." . str_replace('/','.',$item['type']);

            return $item;

            //  Filter out items, which have not a matching template partial
        })->filter(function ($item) {

            return View::exists($item['view']);
        });
    }

    /**
     * Render all components and return as HTML
     */
    public function render() : string
    {
        //  Map over every item and reduce the result to a HTML
        return $this->content()->reduce(function ($container, $item) {
            $attr = null;
            if (isset($item['attributes'])) {
                $attr = json_decode($item['attributes'][0]);
            }

            $view = View::make($item['view'], ['content' => $item['data'], 'attr' => $attr ]);

            //  Append the new result to the result of all previous items
            return $container . $view->render();
        });
    }

}
