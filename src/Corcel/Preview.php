<?php

namespace WP4Laravel\Corcel;

use Illuminate\Http\Request;
use WP4Laravel\Cache\CacheContent;

/**
 * Enable the preview function of Wordpress
 */
trait Preview
{
    /**
     * Get the requested post based on the slug or get the preview of a post
     * @param  Request $request
     * @param  string $slug
     * @return Corcel\Model\Post
     */
    public static function publishedOrPreview(Request $request, $slug)
    {
        //  Determine the ID of the post. For pages this is page_id
        //  For all other post types is this 'p'
        $id = $request->get('preview_id') ?: $request->get('p');
        $id = $id ?: $request->get('page_id');

        //  When slug == __preview, the current call is a new unpublished post
        if ($slug == "__preview") {
            $post = static::where('post_status', 'draft')
                        ->where('ID', $id)
                        ->firstOrFail();
        } else {
            $post = static::current($slug);
        }

        //  This can be used for all post types
        if (($request->has('preview_id') || $request->has('preview')) && $id == $post->ID) {
            $preview = $post->getPreview();

            //  Save the thumbnail as meta when sended through as get parameter
            if ($request->has('_thumbnail_id') && $request->get('_thumbnail_id') > -1) {
                $preview->saveMeta('_thumbnail_id', $request->get('_thumbnail_id'));
            }

            //  Overwrite the post object with the preview object
            $post = $preview;

            //  Flush the content cache
            CacheContent::flush();
        }

        return $post;
    }



    /**
     * Get the preview of a post
     * @return Corcel\Model\Post
     */
    public function getPreview()
    {
        //  Get the latest revision of the post
        if ($revision = $this->revision->last()) {

            //  The revision has to have a updated date later then the original
            if ($revision->updated_at->lt($this->updated_at)) {
                return $this;
            }

            //  What is the status of the original post
            $status = $this->post_status;

            //  Fill the current post with the attributes of the revision
            //  That should be all
            foreach ($revision->attributesToArray() as $key=>$value) {
                // Do not copy the parent and the post_type
                if ($key == 'post_parent' || $key == 'post_type') {
                    continue;
                }

                $this->$key = $value;
            }

            if ($status == 'draft') {

                //  Id the original post is a draft, something odd happens
                //  The default Wordpress customfields will be saved on the draft
                //  Button the ACF fields will be saved on the revision
                //  Below we will save all meta data which is on the draft and
                //  not on the revision save to the revision
                //  So the revision will have all meta data at that point

                $metaOfDraft = $this->meta->mapWithKeys(function ($item) {
                    return [$item->meta_key=>$item->meta_value];
                })->toArray();
                $metaOfRevision = $revision->meta->mapWithKeys(function ($item) {
                    return [$item->meta_key=>$item->meta_value];
                })->toArray();

                //  Save the new meta to the revision
                $revision->saveMeta(array_diff_key($metaOfDraft, $metaOfRevision));
            }

            //  Load the meta again
            $this->load('meta');
        }

        return $this;
    }
}
