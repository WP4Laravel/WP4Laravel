<?php

function my_edit_toolbar($wp_toolbar)
{
    $wp_toolbar->remove_node('site-name');

    $args = [
        'id' => 'site-name',
        'title' => env('APP_NAME') . ' v' . env('API_LATEST_VERSION'),
        'href' => get_home_url(),
    ];
    $wp_toolbar->add_node($args);
}

add_action('admin_bar_menu', 'my_edit_toolbar', 49);
