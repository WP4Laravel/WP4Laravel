<?php

// Register Custom Post Type
add_action('init', function () {
    $labels = [
        'name' => _x('Artworks', 'Artworks', 'wp4laravel'),
        'singular_name' => _x('Artwork', 'Artwork', 'wp4laravel'),
        'menu_name' => __('Artworks', 'wp4laravel'),
        'all_items' => __('All Artworks', 'wp4laravel'),
        'view_item' => __('Show Artworks', 'wp4laravel'),
        'add_new_item' => __('Add Artwork', 'wp4laravel'),
        'add_new' => __('Add Artwork', 'wp4laravel'),
        'edit_item' => __('Edit Artwork', 'wp4laravel'),
        'update_item' => __('Edit Artwork', 'wp4laravel'),
        'search_items' => __('Search', 'wp4laravel'),
        'not_found' => __('Not found', 'wp4laravel'),
        'not_found_in_trash' => __('Not found in trash', 'wp4laravel'),
    ];
    $args = [
        'label' => __('Artworks', 'wp4laravel'),
        'labels' => $labels,
        'supports' => ['title', 'thumbnail', 'author'],
        'taxonomies' => [],
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 42,
        'can_export' => true,
        'has_archive' => false,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'capability_type' => 'post',
        'menu_icon' => 'dashicons-images-alt',
    ];
    register_post_type('artwork', $args);
}, 0);
