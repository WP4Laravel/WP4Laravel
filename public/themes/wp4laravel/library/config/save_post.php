<?php

add_action('save_post', 'check_tourhighlight_parent');

function check_tourhighlight_parent($post_id)
{
    $post_type = get_post_type($post_id);

    if ('tour' === $post_type) {
        update_post_meta($post_id, 'zip_size', null);
    }

    if ('tourhighlight' === $post_type) {
        $parent_posts = get_posts([
            'post_type' => 'tour',
            'meta_query' => [
                [
                    'key' => 'tourhighlight_collection',
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
