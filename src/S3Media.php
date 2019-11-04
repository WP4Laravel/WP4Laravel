<?php

/**
 * S3 Media
 * Handles URL's of media and thumbnails which are stored in a S3 bucket
 * @param  mixed $image
 * @return this
 */

namespace WP4Laravel;

use Illuminate\Support\Facades\Storage;
use Corcel\Acf\Field\Image;
use Corcel\Acf\Field\File;
use Corcel\Model\Attachment;
use Corcel\Model\Meta\ThumbnailMeta;

class S3Media
{
    /**
     * Stores the media object given in the constructor
     */
    protected $media;

    /**
     * Stores the S3 meta data from the media object
     */
    protected $s3info;

    /**
     * Static method, to create a cleaner way of making an instance
     * @param  mixed $image
     */
    public static function handle($image)
    {
        //  Return a new instance of this class
        return (new static($image));
    }

    /**
     * Create a new instance with the given media object
     * @param mixed $media
     */
    public function __construct($media)
    {
        //  Save the media object as object property
        $this->media = $media;

        //  Check if the given media object is an instance of
        //  Image (corcel/acf) or ThumbnailMeta (corcel/corcel)
        if ($media instanceof Image
            || $media instanceof File
            || $media instanceof ThumbnailMeta) {
            $this->s3info = unserialize($this->media->attachment->meta->amazonS3_info);
        } elseif ($media instanceof Attachment) {
            $this->s3info = unserialize($this->media->meta->amazonS3_info);
        }
    }

    /**
     * Get the full path of the media object
     * @return string|null
     */
    public function path()
    {
        //  Does the meta data with S3 data exists within the media object
        if (empty($this->s3info['key'])) {
            return null;
        }

        //  Get the path of the media object, trim off the slashes
        $filename = trim($this->s3info['key'], '/');

        return $filename;
    }

    /**
     * Get the url of the media object
     * @return string|null
     */
    public function url()
    {
        //  Get the path of the file
        if (!$filename = $this->path()) {
            return null;
        }

        //  Check if the file exists on the S3 bucket
        //  If so, return the url of the file
        if (Storage::disk('s3')->exists($filename)) {
            return Storage::disk('s3')->url($filename);
        }

        //  File does not exists, return null
        return null;
    }

    /**
     * Get the URL of the given size of the media
     * @param  string $size
     * @return string | null
     */
    public function size($size)
    {
        //  Get the path of the file
        if (!$filename = $this->path()) {
            return null;
        }

        //  Get all available sizes of the file
        if (isset($this->media->attachment->meta)) {
            $sizes = unserialize($this->media->attachment->meta->_wp_attachment_metadata)['sizes'];
        } else {
            $sizes = unserialize($this->media->meta->_wp_attachment_metadata)['sizes'];
        }

        //  Check if the requested size exists or the requested size is the original
        //  If so return the url of the original file
        if (!isset($sizes[$size]) || $size == 'full') {
            return $this->url();
        }

        //  Create the filename of the size
        $filename = dirname($filename) . '/' . $sizes[$size]['file'];

        //  Check if the file exists on the S3 bucket
        //  If so, return the url of the size
        if (Storage::disk('s3')->exists($filename)) {
            return Storage::disk('s3')->url($filename);
        }

        //  File does not exists, return null
        return null;
    }
}
