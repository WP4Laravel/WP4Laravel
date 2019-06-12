<?php

// Register Custom Post Type
add_action('init', function () {
    $labels = [
        'name' => _x('Activities', 'Activities', 'wp4laravel'),
        'singular_name' => _x('Activity', 'Activity', 'wp4laravel'),
        'menu_name' => __('Activities', 'wp4laravel'),
        'all_items' => __('All Activities', 'wp4laravel'),
        'view_item' => __('Show Activities', 'wp4laravel'),
        'add_new_item' => __('Add Activity', 'wp4laravel'),
        'add_new' => __('Add Activity', 'wp4laravel'),
        'edit_item' => __('Edit Activity', 'wp4laravel'),
        'update_item' => __('Edit Activity', 'wp4laravel'),
        'search_items' => __('Search', 'wp4laravel'),
        'not_found' => __('Not found', 'wp4laravel'),
        'not_found_in_trash' => __('Not found in trash', 'wp4laravel'),
    ];
    $args = [
        'label' => __('Activities', 'wp4laravel'),
        'labels' => $labels,
        'supports' => ['title', 'author'],
        'taxonomies' => [],
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 44,
        'can_export' => true,
        'has_archive' => false,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'capability_type' => 'post',
        'menu_icon' => 'dashicons-calendar-alt',
    ];
    register_post_type('activity', $args);
}, 0);
