<?php

/**
 * Check if exhibition or linked artworks are updated and zip_size must be reset
 */
function check_exhibition_parent($post_id)
{
    $post_type = get_post_type($post_id);

    if ('exhibition' === $post_type) {
        update_post_meta($post_id, 'zip_size', null);
    }

    if ('artwork' === $post_type) {
        $parent_posts = get_posts([
            'post_type' => 'exhibition',
            'meta_query' => [
                [
                    'key' => 'artworks',
                    'value' => '"' . $post_id . '"',
                    'compare' => 'LIKE'
                ]
            ]
        ]);

        $now = current_time('mysql');

        foreach ($parent_posts as $post) {
            $post->post_modified = $now;
            $post->post_modified_gmt = get_gmt_from_date($now);

            wp_update_post($post);

            update_post_meta($post->ID, 'zip_size', null);
        }
    }

    return;
}
add_action('save_post', 'check_exhibition_parent', 9);
