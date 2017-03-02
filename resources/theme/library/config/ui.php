<?php

/*
 |--------------------------------------------------------------------------
 | Plate
 |--------------------------------------------------------------------------
 |
 | Plate provides a bunch of handy WordPress defaults to help you get the
 | most out of WordPress development.
 |
 | Please see https://github.com/wordplate/plate
 |
 */

/*
 * Remove menu items.
 */
add_theme_support('wp4laravel-menu', [
   'comments',
   'links',
   'tools',
   'dashboard'
]);

/*
 * Remove meta boxes in post editor.
 */
add_theme_support('wp4laravel-editor', [
    'commentsdiv',
    'commentstatusdiv',
    'linkadvanceddiv',
    'linktargetdiv',
    'linkxfndiv',
    'postcustom',
    'revisionsdiv',
    'slugdiv',
    'sqpt-meta-tags',
    'trackbacksdiv',
    //'categorydiv',
    //'tagsdiv-post_tag',
]);

/*
 * Remove dashboard widgets.
 */
add_theme_support('wp4laravel-dashboard', [
    'dashboard_activity',
    'dashboard_incoming_links',
    'dashboard_plugins',
    'dashboard_recent_comments',
    'dashboard_primary',
    'dashboard_quick_press',
    'dashboard_recent_drafts',
    'dashboard_secondary',
    //'dashboard_right_now',
]);

/*
 * Remove links from admin toolbar.
 */
add_theme_support('wp4laravel-toolbar', [
    'comments',
    'wp-logo',
    'edit',
    'appearance',
    'view',
    'new-content',
    'updates',
    'search',
]);

/*
 * Remove dashboard tabs.
 */
add_theme_support('wp4laravel-tabs', ['help']);

/*
 * Set custom login logo.
 */
add_theme_support('wp4laravel-login', sprintf('%s/%s', get_template_directory_uri(), '/screenshot.png'));

/*
 * Set custom footer text.
 */
add_theme_support('wp4laravel-footer', 'Thank you for using Wordpress with Laravel');

/*
 * Set theme defaults.
 */
add_action('after_setup_theme', function () {
    // Show the admin bar.
    show_admin_bar(false);

    // Add post thumbnails support.
    add_theme_support('post-thumbnails');

    // Add support for post formats.
    //add_theme_support('post-formats', ['aside', 'audio', 'gallery', 'image', 'link', 'quote', 'video']);

    // Add title tag theme support.
    add_theme_support('title-tag');

    // Add HTML5 support.
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'widgets',
    ]);

    // Add primary WordPress menu.
    register_nav_menu('primary-menu', __('Primary Menu', 'wp4laravel'));
});


/*
 * Remove JPEG compression.
 */
add_filter('jpeg_quality', function () {
    return 100;
}, 10, 2);
