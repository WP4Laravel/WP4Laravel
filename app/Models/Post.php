<?php

namespace App\Models;

use Corcel\Model\Post as Corcel;
use WP4Laravel\S3Media;
use App\Widget\WidgetCollection;
use WP4Laravel\Multilanguage\Translatable as WP4LaravelTranslatable;

class Post extends Corcel
{
    use WP4LaravelTranslatable;

    public function flex($attribute = 'layout')
    {
        return new \WP4Laravel\Flex($this, $attribute);
    }

    public function widgets()
    {
        $widgetCollection = new WidgetCollection($this, 'widget_collection');

        return $widgetCollection->widgets();
    }

    public function getPostImages($size = 'header')
    {
        if (!$this->thumbnail) {
            if (($this->postType == 'tourhighlight' && (bool)$this->meta->has_detail) || ($this->postType != 'tourhighlight')) {
                abort(500, 'This item has no featured image! [' . $this->postType . ': ' . $this->ID . ', ' . $this->title . ']');
            }
        }

        return [
            'thumbnail' => S3Media::handle($this->thumbnail)->size('thumbnail'),
            'header' => S3Media::handle($this->thumbnail)->size($size),
            'full' => $this->headerImages($this->thumbnail),
        ];
    }

    public function headerImages($default)
    {
        $main_image = S3Media::handle($default)->url();
        if ($main_image) {
            $output[] = $main_image;
        } else {
            $output = null;
        }
        foreach ($this->acf->repeater('extra_header_images') as $item) {
            $output[] = S3Media::handle($item['image'])->url();
        };
        return $output;
    }

    public function toArray()
    {
        return [
            'id' => $this->ID,
            'title' => ltrim($this->title, '#* '),
            'slug' => $this->slug,
            'images' => $this->getPostImages(),
            'date' => \OutputHelper::formatDate($this->post_date),
        ];
    }

    public function scopeCustomFilter($query, $key, $value)
    {
        return $query->join('postmeta', 'posts.id', '=', 'postmeta.post_id')->where('postmeta.meta_key', $key)->where('postmeta.meta_value', $value);
    }
}
