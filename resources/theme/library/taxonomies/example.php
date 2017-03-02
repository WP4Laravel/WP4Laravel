<?php

// hook into the init action and call create_book_taxonomies when it fires

add_action('init', function () {
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = array(
        'name'              => _x('Examples', 'Examples', 'wp4laravel'),
        'singular_name'     => _x('Example', 'Example', 'wp4laravel'),
        'search_items'      => __('Search Examples', 'wp4laravel'),
        'all_items'         => __('All Examples', 'wp4laravel'),
        'edit_item'         => __('Edit Example', 'wp4laravel'),
        'update_item'       => __('Update Example', 'wp4laravel'),
        'add_new_item'      => __('Add New Example', 'wp4laravel'),
        'new_item_name'     => __('New Example', 'wp4laravel'),
        'menu_name'         => __('Examples', 'wp4laravel'),
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
