<?php

// Register Custom Post Type
add_action('init', function () {
    $labels = [
        'name' => _x('Exhibitions', 'Exhibitions', 'wp4laravel'),
        'singular_name' => _x('Exhibition', 'Exhibition', 'wp4laravel'),
        'menu_name' => __('Exhibitions', 'wp4laravel'),
        'all_items' => __('All Exhibitions', 'wp4laravel'),
        'view_item' => __('Show Exhibitions', 'wp4laravel'),
        'add_new_item' => __('Add Exhibition', 'wp4laravel'),
        'add_new' => __('Add Exhibition', 'wp4laravel'),
        'edit_item' => __('Edit Exhibition', 'wp4laravel'),
        'update_item' => __('Edit Exhibition', 'wp4laravel'),
        'search_items' => __('Search', 'wp4laravel'),
        'not_found' => __('Not found', 'wp4laravel'),
        'not_found_in_trash' => __('Not found in trash', 'wp4laravel'),
    ];
    $args = [
        'label' => __('Exhibitions', 'wp4laravel'),
        'labels' => $labels,
        'supports' => ['title', 'thumbnail', 'author'],
        'taxonomies' => [],
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 40,
        'can_export' => true,
        'has_archive' => false,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'capability_type' => 'post',
        'menu_icon' => 'dashicons-camera-alt',
    ];
    register_post_type('exhibition', $args);
}, 0);
