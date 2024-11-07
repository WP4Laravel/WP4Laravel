# Changelog

## v2.1.1

Fix rendering of `ImageFake` pictures. It tried to get the image size from S3 when `picture.use_aws_storage` was set to `true`. But fake images are not stored in S3.

## v2.1.0

The release is about performance improvements. You should notice a drop in the amount of database queries.

`MenuBuilder::menuForLocation()` has been improved. Ensure your theme name is `wp4laravel`.

## v2.0.0

Laravel 10 and Corcel 7 are the minimal versions for this package.

## v1.2.0

Removed the `aura/autoload` dependency. This autoloader was used to load specific dependencies in the WordPress theme. This is no longer needed because conflicts between WordPress, `laravel/helpers` and Laravels `__()` are resolved.

Get the newest `wp-config.php` by running `php artisan vendor:publish --tag=wp4laravel-wp-config --force`. This will replace your existing `wp-config.php` file with the newest version. Make sure to leaf your project specific changes in the new file.

The newest `wp-config.php` file can read the S3 offload configuration from environmental variables. Ensure your environmental variables are up to date with this change. Or don't add the part of `wp-config.php` to stay backwards compatible.

## v1.1.0

Removed the `laravel/helpers` dependency. Refactor your code to use the Laravel helpers directly: https://laravel.com/docs/11.x/helpers

Or add `"laravel/helpers": ">1.2"` to your `composer.json` file.

## v1.0.0

Pin the version of `laravel/framework` to `^9.0` in the `composer.json` file.

## v0.16.0

Add Laravel 9 support

## v0.15.0

Add Laravel 7 support
