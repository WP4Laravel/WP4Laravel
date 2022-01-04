<?php

namespace WP4Laravel\Corcel;

use Corcel\Model;
use Illuminate\Support\Facades\Cache;
use WP4Laravel\Cache\CachePost;

/**
 * Class OffloadedMedia
 *
 * @package Corcel\Model
 * @author Bas de Beer <bas.de.beer@in10.nl>
 */
class OffloadedMedia extends Model
{
    /**
     * @var string
     */
    protected $table = 'as3cf_items';

    /**
     * @var string
     */
    protected $primaryKey = 'source_id';

    /**
     * Find the post on media post id
     *
     * @param Model $post
     * @return OffloadedMedia|null
     */
    public static function findById(Model $media) : ?OffloadedMedia
    {

        // Apply caching based on media post_modified timestamp
        $timestamp = $media->post_modified->timestamp;
        return Cache::rememberForever("media.offloaded.{$timestamp}", function () use ($media) {
            return static::find($media->ID);
        });
    }

    /**
     * Get amazon s3 info from media post
     *
     * @return array
     */
    public function getAmazonS3Info() : array
    {
        return [
            'provider' => $this->provider,
            'region' => $this->region,
            'bucket' => $this->bucket,
            'key' => $this->original_path
        ];
    }
}
