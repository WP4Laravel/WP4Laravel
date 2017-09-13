# WP4Laravel

A bunch of helper classes to make a headless setup of Wordpress with Laravel great.

>Note: For Laravel 5.4 or lower, use the 0.5 branch!

## Installation

```
composer require wp4laravel/wp4laravel
```

Add the Service Provider of the WP4Laravel package to your config/app.php

```
WP4Laravel\WP4LaravelServiceProvider::class
```

##	Features

###	Site container

###	Flexible layout

### Menu builder

### WP Autop

###	SEO

### Picture element

###	S3 media
The Corcel libraries doesn't support media posts from external storage like an S3 bucket. This wrapper adds this support to get url's of the original files but also the url's of the generated thumbnails.

#### Requirements

* Laravel configured with S3 storage
* Wordpress configured with the S3 Offload plugin

#### Usage

Get the url of the featured image of a post

```
\WP4Laravel\S3Media::handle($post->thumbnail)->url();
```

The same from the site container in a blade template

```
{{ $site->s3($post->thumbnail)->url() }}
```

Get the url of the 'large' crop from the media object

```
{{ $site->s3($post->thumbnail)->size('large') }}
```

Get the url of an ACF Image field

```
{{ $site->s3($post->acf->image('my_image_field'))->url() }}
```

Because of the main usage in a blade template, the S3Media object does not generate exceptions. If something is wrong (bad input, file not exists) the url() and site() methods just returns null.

### Cache
