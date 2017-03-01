<?php

// hook into the init action and call create_book_taxonomies when it fires

add_action('init', function () {
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = array(
        'name'              => _x('Examples', 'Examples', 'laravel'),
        'singular_name'     => _x('Example', 'Example', 'laravel'),
        'search_items'      => __('Search Examples', 'laravel'),
        'all_items'         => __('All Examples', 'laravel'),
        'edit_item'         => __('Edit Example', 'laravel'),
        'update_item'       => __('Update Example', 'laravel'),
        'add_new_item'      => __('Add New Example', 'laravel'),
        'new_item_name'     => __('New Example', 'laravel'),
        'menu_name'         => __('Examples', 'laravel'),
    );

    $args = array(
        'hierarchical'      => false,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => false,
    );

    register_taxonomy('example', array( 'post' ), $args);
});
