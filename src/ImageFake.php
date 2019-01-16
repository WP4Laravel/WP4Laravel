<?php

namespace WP4Laravel;

use Illuminate\Support\Collection;

/**
 * Fake variant of \Corcel\Model\Meta\ThumbnailMeta for use with in10\styleguide.
 * Initialize as follows, and use as input for wp4laravel::picture:
 *
 * ImageFake::make([
 *     'full' => '/build/imgs/test1-wide.png',
 *     '220w' => '/build/imgs/test1-wide.png',
 *     '440w' => '/build/imgs/test1-square.png',
 * ]);
 */
class ImageFake
{
    /*
     * Construction
     */
    public static function make(array $urls, $alt = null)
    {
        return new self($urls, $alt);
    }

    private $sizes;

    private function __construct(array $urls, $alt)
    {
        $this->sizes = (new Collection($urls))->map(function ($size) {
            return ['url' => $size];
        });

        $this->url = $this->sizes->first()['url'];

        $this->attachment = (object) [
            'meta' => (object) [
                '_wp_attachment_metadata' => serialize([
                    'sizes' => $this->sizes->toArray(),
                ]),
                '_wp_attached_file' => $this->sizes->first()['url'],
                '_wp_attachment_image_alt' => $alt,
            ],
        ];
    }

    /*
     * Public UI à la \Corcel\Model\Meta\ThumbnailMeta
     */
    public $attachment;
    public $url;

    public function size($size)
    {
        if ($size === 'full') {
            return $this->sizes['full']['url'];
        }

        return (object) $this->sizes[$size];
    }

    public function __toString()
    {
        return $this->sizes->first()['url'];
    }
}
