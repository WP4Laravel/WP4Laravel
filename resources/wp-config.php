<?php

// Require the dotenv package using a custom autoloader
// (composer autoloading doesn't work here, because the laravel helpers conflict with wordpress)
$vendor = dirname(__DIR__) . '/vendor/';
require_once $vendor . 'aura/autoload/autoload.php';
$loader = new \Aura\Autoload\Loader();
$loader->register();
$loader->addPrefix('Dotenv', $vendor . 'vlucas/phpdotenv/src');

function env(string $key, $default = null)
{
    $value = getenv($key);

    if ($value === false) {
        return $default;
    }

    return $value;
}


/* Register the composer auto loader. */

/* Detect the environment. */
(new Dotenv\Dotenv(__DIR__.'/..'))->load();

/* MySQL database name. */
define('DB_NAME', env('DB_DATABASE'));

/* MySQL database username. */
define('DB_USER', env('DB_USERNAME'));

/* MySQL database password. */
define('DB_PASSWORD', env('DB_PASSWORD'));

/* MySQL hostname. */
define('DB_HOST', env('DB_HOST'));

/* Database Charset to use in creating database tables. */
define('DB_CHARSET', env('DB_CHARSET', 'utf8mb4'));

/* The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', env('DB_COLLATE', ''));

/* Set the home url to the current domain. */
define('WP_HOME', env('APP_URL'));


/* Custom WordPress directory. */
define('WP_SITEURL', WP_HOME.'/'.env('WP_DIR', 'wp'));

/* Custom content directory. */
define('WP_CONTENT_DIR', env('WP_CONTENT_DIR', __DIR__));
define('WP_CONTENT_URL', env('WP_CONTENT_URL', WP_HOME));


define('WP_TEMPLATE_DIR', env('WP_CONTENT_DIR', __DIR__.'/../resources/views/'));

/* Set the trash to less days to optimize WordPress. */
define('EMPTY_TRASH_DAYS', env('EMPTY_TRASH_DAYS', 7));

/* Set the default WordPress theme. */
define('WP_DEFAULT_THEME', env('WP_THEME', 'wp4laravel'));

/* Specify the Number of Post Revisions. */
define('WP_POST_REVISIONS', env('WP_POST_REVISIONS', 2));

/* WordPress environment. */
define('WP_ENV', env('APP_ENV', 'production'));

/* Cleanup image edits. */
define('IMAGE_EDIT_OVERWRITE', env('IMAGE_EDIT_OVERWRITE', true));

/* Prevent file edit from the dashboard. */
define('DISALLOW_FILE_EDIT', env('DISALLOW_FILE_EDIT', true));

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', env('AUTH_KEY'));
define('SECURE_AUTH_KEY', env('SECURE_AUTH_KEY'));
define('LOGGED_IN_KEY', env('LOGGED_IN_KEY'));
define('NONCE_KEY', env('NONCE_KEY'));
define('AUTH_SALT', env('AUTH_SALT'));
define('SECURE_AUTH_SALT', env('SECURE_AUTH_SALT'));
define('LOGGED_IN_SALT', env('LOGGED_IN_SALT'));
define('NONCE_SALT', env('NONCE_SALT'));

/**#@-*/

/*
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = env('WP_PREFIX', 'wp_');

/*
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', env('APP_DEBUG', false));
define('WP_DEBUG_DISPLAY', env('APP_DEBUG', false));
define('SCRIPT_DEBUG', env('APP_DEBUG', false));

/* Add multisite support. */
define('WP_ALLOW_MULTISITE', env('WP_ALLOW_MULTISITE', false));

if (env('WP_MULTISITE', false)) {
    define('MULTISITE', env('WP_MULTISITE', false));
    define('SUBDOMAIN_INSTALL', env('SUBDOMAIN_INSTALL', false));
    define('DOMAIN_CURRENT_SITE', env('DOMAIN_CURRENT_SITE', $_SERVER['HTTP_HOST']));
    define('PATH_CURRENT_SITE', env('PATH_CURRENT_SITE', '/'));
    define('SITE_ID_CURRENT_SITE', env('SITE_ID_CURRENT_SITE', 1));
    define('BLOG_ID_CURRENT_SITE', env('BLOG_ID_CURRENT_SITE', 1));
}

/* That's all, stop editing! Happy blogging. */

/* Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__.'/'.env('WP_DIR', 'wp'));
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH.'wp-settings.php';
