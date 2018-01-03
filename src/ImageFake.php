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
    public static function make(array $urls)
    {
        return new self($urls);
    }

    private $sizes;

    private function __construct(array $urls)
    {
        $this->sizes = (new Collection($urls))->map(function ($size) {
            return ['url' => $size];
        });

        $this->attachment = (object) [
            'meta' => (object) [
                '_wp_attachment_metadata' => serialize([
                    'sizes' => $this->sizes->toArray(),
                ]),
                '_wp_attached_file' => $this->sizes->first()['url'],
                '_wp_attachment_image_alt' => 'Some alt-text',
            ],
        ];
    }

    /*
     * Public UI à la \Corcel\Model\Meta\ThumbnailMeta
     */
    public $attachment;

    public function size($size)
    {
        return (object) $this->sizes[$size];
    }

    public function __toString()
    {
        return $this->sizes->first()['url'];
    }
}
