<?php

// Register Custom Post Type
add_action('init', function () {
    $labels = [
        'name' => _x('Menulinks', 'Menulinks', 'wp4laravel'),
        'singular_name' => _x('Menulink', 'Menulink', 'wp4laravel'),
        'menu_name' => __('Menulinks', 'wp4laravel'),
        'all_items' => __('All Menulinks', 'wp4laravel'),
        'view_item' => __('Show Menulinks', 'wp4laravel'),
        'add_new_item' => __('Add Menulink', 'wp4laravel'),
        'add_new' => __('Add Menulink', 'wp4laravel'),
        'edit_item' => __('Edit Menulink', 'wp4laravel'),
        'update_item' => __('Edit Menulink', 'wp4laravel'),
        'search_items' => __('Search', 'wp4laravel'),
        'not_found' => __('Not found', 'wp4laravel'),
        'not_found_in_trash' => __('Not found in trash', 'wp4laravel'),
    ];
    $args = [
        'label' => __('Menulinks', 'wp4laravel'),
        'labels' => $labels,
        'supports' => ['title', 'author'],
        'taxonomies' => [],
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 43,
        'can_export' => true,
        'has_archive' => false,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'capability_type' => 'post',
        'menu_icon' => 'dashicons-admin-links',
    ];
    register_post_type('menulink', $args);
}, 0);