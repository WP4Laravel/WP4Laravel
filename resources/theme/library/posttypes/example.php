<?php

// Register Custom Post Type
add_action('init', function () {
    $labels = array(
        'name'                => _x('Examples', 'Examples', 'wp4laravel'),
        'singular_name'       => _x('Example', 'Example', 'wp4laravel'),
        'menu_name'           => __('Examples', 'wp4laravel'),
        'all_items'           => __('All Examples', 'wp4laravel'),
        'view_item'           => __('Show Examples', 'wp4laravel'),
        'add_new_item'        => __('Add Example', 'wp4laravel'),
        'add_new'             => __('Add Example', 'wp4laravel'),
        'edit_item'           => __('Edit Example', 'wp4laravel'),
        'update_item'         => __('Edit Example', 'wp4laravel'),
        'search_items'        => __('Search', 'wp4laravel'),
        'not_found'           => __('Not found', 'wp4laravel'),
        'not_found_in_trash'  => __('Not found in trash', 'wp4laravel'),
    );
    $args = array(
        'label'               => __('Examples', 'wp4laravel'),
        'labels'              => $labels,
        'supports'            => array( 'title', 'thumbnail', 'excerpt'),
        'taxonomies'          => array( ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 40,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'capability_type'     => 'post',
    );
    register_post_type('example', $args);
}, 0);
