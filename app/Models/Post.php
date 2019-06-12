<?php

namespace App\Models;

use Corcel\Model\Post as Corcel;
use WP4Laravel\S3Media;
use WP4Laravel\Multilanguage\Translatable as WP4LaravelTranslatable;

class Post extends Corcel
{
    use WP4LaravelTranslatable;

    public function getPostImages($size = 'header')
    {
        if (!$this->thumbnail) {
            if ($this->postType == 'exhibition' && $this->postType == 'artwork') {
                abort(500, 'This item has no featured image! [' . $this->postType . ': ' . $this->ID . ', ' . $this->title . ']');
            }
        }

        return [
            'thumbnail' => S3Media::handle($this->thumbnail)->size('thumbnail'),
            'header' => S3Media::handle($this->thumbnail)->size($size),
            'full' => S3Media::handle($this->thumbnail)->url(),
        ];
    }

    public function toArray()
    {
        return [
            'id' => $this->ID,
            'title' => $this->title,
            'slug' => $this->slug,
        ];
    }
}
