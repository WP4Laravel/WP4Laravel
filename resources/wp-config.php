<?php

// Preload WordPress l10n functions. This is a trick to avoid the "Cannot redeclare __()" error.
// The function won't be loaded again, because Laravel checks if the function already exists.
require __DIR__ . '/wp/wp-includes/l10n.php';

/* Register the composer autoloader. */
require __DIR__.'/../vendor/autoload.php';

/* Detect the environment. */
Dotenv\Dotenv::createUnsafeImmutable(__DIR__.'/..')->load();

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
define('WP_DEBUG_LOG', env('APP_DEBUG', false));
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

if (env('AWS_ACCESS_KEY_ID') || env('AS3CF_USE_SERVER_ROLES')) {
    define( 'AS3CF_SETTINGS', serialize( array(
        // Storage Provider ('aws', 'do', 'gcp')
        'provider' => 'aws',
        // Access Key ID for Storage Provider (aws and do only, replace '*')
        'access-key-id' => env('AWS_ACCESS_KEY_ID'),
        // Secret Access Key for Storage Providers (aws and do only, replace '*')
        'secret-access-key' => env('AWS_SECRET_ACCESS_KEY'),
        // Use IAM Roles on Amazon Elastic Compute Cloud (EC2) or Google Compute Engine (GCE)
        'use-server-roles' => env('AS3CF_USE_SERVER_ROLES', false),
        // Bucket to upload files to
        'bucket' => env('AWS_BUCKET'),
        // Bucket region (e.g. 'us-west-1' - leave blank for default region)
        'region' => env('AWS_DEFAULT_REGION'),
        // Automatically copy files to bucket on upload
        'copy-to-s3' => true,
        // Enable object prefix, useful if you use your bucket for other files
        'enable-object-prefix' => true,
        // Object prefix to use if 'enable-object-prefix' is 'true'
        'object-prefix' => 'storage/',
        // Organize bucket files into YYYY/MM directories matching Media Library upload date
        'use-yearmonth-folders' => true,
        // Append a timestamped folder to path of files offloaded to bucket to avoid filename clashes and bust CDN cache if updated
        'object-versioning' => true,
        // Delivery Provider ('storage', 'aws', 'do', 'gcp', 'cloudflare', 'keycdn', 'stackpath', 'other')
        'delivery-provider' => 'aws',
        // Rewrite file URLs to bucket
        'serve-from-s3' => true,
        // Use a custom domain (CNAME), not supported when using 'storage' Delivery Provider
        'enable-delivery-domain' => true,
        // Custom domain (CNAME), not supported when using 'storage' Delivery Provider
        'delivery-domain' => str_replace(['https://', 'http://'], '', env('AWS_URL')),
        // Enable signed URLs for Delivery Provider that uses separate key pair (currently only 'aws' supported, a.k.a. CloudFront)
        'enable-signed-urls' => false,
        // Access Key ID for signed URLs (aws only, replace '*')
        'signed-urls-key-id' => '********************',
        // Key File Path for signed URLs (aws only, absolute file path, not URL)
        // Make sure hidden from public website, i.e. outside site's document root.
        'signed-urls-key-file-path' => '/path/to/key/file.pem',
        // Private Prefix for signed URLs (aws only, relative directory, no wildcards)
        'signed-urls-object-prefix' => 'private/',
        // Serve files over HTTPS
        'force-https' => false,
        // Remove the local file version once offloaded to bucket
        'remove-local-file' => false,
    ) ) );
}

/* That's all, stop editing! Happy blogging. */

/* Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__.'/'.env('WP_DIR', 'wp'));
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH.'wp-settings.php';
