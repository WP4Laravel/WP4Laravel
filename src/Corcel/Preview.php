<?php

namespace WP4Laravel\Corcel;

use Illuminate\Http\Request;

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
            $post = $post->getPreview();
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
            //  Fill the current post with the attributes of the revision
            //  That should be all
            foreach ($revision->attributesToArray() as $key=>$value) {
                $this->$key = $value;
            }
        }

        return $this;
    }
}
