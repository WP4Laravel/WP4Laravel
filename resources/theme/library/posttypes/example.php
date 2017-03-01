<?php

// Register Custom Post Type
add_action('init', function () {
    $labels = array(
        'name'                => _x('Examples', 'Examples', 'laravel'),
        'singular_name'       => _x('Example', 'Example', 'laravel'),
        'menu_name'           => __('Examples', 'laravel'),
        'all_items'           => __('All Examples', 'laravel'),
        'view_item'           => __('Show Examples', 'laravel'),
        'add_new_item'        => __('Add Example', 'laravel'),
        'add_new'             => __('Add Example', 'laravel'),
        'edit_item'           => __('Edit Example', 'laravel'),
        'update_item'         => __('Edit Example', 'laravel'),
        'search_items'        => __('Search', 'laravel'),
        'not_found'           => __('Not found', 'laravel'),
        'not_found_in_trash'  => __('Not found in trash', 'laravel'),
    );
    $args = array(
        'label'               => __('Examples', 'laravel'),
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
