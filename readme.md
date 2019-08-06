# WP4Laravel - A headless Wordpress concept

## Table of contents
- [WP4Laravel - A headless Wordpress concept](#wp4laravel---a-headless-wordpress-concept)
  * [Table of contents](#table-of-contents)
  * [Supported versions](#supported-versions)
  * [The concept](#the-concept)
  * [Dependencies](#dependencies)
  * [Installation](#installation)
    + [Gitignore](#gitignore)
    + [Composer](#composer)
    + [Environment file.](#environment-file)
    + [Database config](#database-config)
    + [Service provider](#service-provider)
    + [Install Corcel](#install-corcel)
    + [Publish public data](#publish-public-data)
    + [Storage](#storage)
    + [Remove unused migrations](#remove-unused-migrations)
    + [Install Wordpress](#install-wordpress)
  * [Basic usage](#basic-usage)
  * [References](#references)
  * [Advanced Custom Fields](#advanced-custom-fields)
  * [Wordpress configuration](#wordpress-configuration)
  * [Add plugins](#add-plugins)
    + [How do I use it?](#how-do-i-use-it)
  * [Multilanguage](#multilanguage)
    + [Translatable models](#translatable-models)
    + [Translatable taxonomies](#translatable-taxonomies)
    + [Making translatable menu's](#making-translatable-menus)
  * [Best practices](#best-practices)
    + [Create your own models for each post type](#create-your-own-models-for-each-post-type)
    + [Register your post types](#register-your-post-types)
    + [Catch-all your pages](#catch-all-your-pages)
    + [Setup your homepage](#setup-your-homepage)
    + [Get the url of a page](#get-the-url-of-a-page)
    + [Rendering \<picture\> tags](#rendering--tags)
      - [Usage](#usage)
      - [Using ImageFake in the styleguide](#using-imagefake-in-the-styleguide)
    + [Using the MenuBuilder to construct menus](#using-the-menubuilder-to-construct-menus)
      - [Example usage](#example-usage)
    + [Translated menu's](#translated-menus)
    + [Activate WP preview function](#activate-wp-preview-function)
    + [SEO tags for models](#seo-tags-for-models)
    + [Hosting assets on S3](#hosting-assets-on-s3)
      - [Requirements](#requirements)
      - [Usage](#usage-1)
    + [RSS-feeds](#rss-feeds)
      - [Example usage](#example-usage-1)

## Supported versions
Only the latest branch of WP4Laravel is supported at any time.

## The concept
WP4Laravel is by default a standard Laravel project. Instead of using a relational database it uses Wordpress as Content Management System.

The benefits relative to a standard Wordpress Setup:

* Use MVC-principles
* Better performance
* Flexibility
* Sustainability
* Security

The benefits relative to a standard Laravel project:

* No need to create a custom CMS
* Get the best of great Wordpress plugins
* For commercial purposes, you can sell the customer a Wordpress CMS.

## Dependencies

The basis of WP4Laravel is just a fresh Laravel install. We add three open source projects in the mix:
* Wordpress as a dependency (https://github.com/johnpbloch/wordpress)
* Corcel: Get Wordpress data with Eloquent (https://github.com/corcel/corcel)
* Wordplate: Standard theme and plugin for Wordpress (only for inspiration, not actually installed)

## Installation

Start a fresh Laravel 5.4+ install

```bash
composer create-project --prefer-dist laravel/laravel:"5.7.*" my-wp-project
```

### Gitignore

Add the following rules to your `.gitignore`

```gitignore
public/languages
public/plugins
public/mu-plugins
public/upgrade
public/uploads
public/wp
```

### Composer

To use Wordpress as a dependency, you need to extend your composer.json. Add a repositories section to the composer.json and ad the following repositories:

```json
"repositories": [
    {
        "type": "composer",
        "url": "https://wpackagist.org"
    }
],
```

Add an 'extra' section to the composer.json, to determine where to install Wordpress and plugins.

```json
"extra": {
    "installer-paths": {
        "public/plugins/{$name}": ["type:wordpress-plugin"]
    },
    "wordpress-install-dir": "public/wp"
}
```

Add the following packages to your installation by executing on the command-line:

```bash
composer require "composer/installers"
composer require "johnpbloch/wordpress"
composer require "wp4laravel/wp4laravel"
composer require "wp4laravel/wp4laravel-plugin"
composer update
```

### Environment file.

* Add the Wordpress salts to your .env file, with http://wordplate.github.io/salt/
* Make sure you added a database and added your database credentials to your .env file.
* Make sure the APP_URL environment variable, matches your local environment.
* Remove all settings that are not needed for your project, such as redis and email.

### Database config

Copy the default mysql connection and name it 'wordpress'. Set the table prefix of the database connection to 'wp\_' in `config/database.php`.

### Service provider

>Note: Only if using Laravel 5.4 or below.

Add the Service Provider of the WP4Laravel package to your config/app.php

```php
WP4Laravel\WP4LaravelServiceProvider::class
```


### Install Corcel
Publish the corcel config file:
```bash
php artisan vendor:publish --provider="Corcel\Laravel\CorcelServiceProvider"
```
Open the app/corcel.php config file and define your database connection.

If you're using Laravel 5.4 or earlier, you need to configure the CorcelServiceProvider. Add the following line to your config/app.php under "Package Service Providers":

```php
Corcel\Laravel\CorcelServiceProvider::class,
```

### Publish public data

Unfortunately, the base theme and config of Wordpress has to be inside the webroot. You can publish these from WP4LaravelServiceProvider.

```bash
php artisan vendor:publish --provider="WP4Laravel\WP4LaravelServiceProvider"
```

### Storage

All Wordpress media will be saved in the location of the Laravel Public storage. To make this work, run the following Artisan command to make a symbolic link in your webroot.

```bash
php artisan storage:link
```

### Remove unused migrations
If you're only using tables managed by Wordpress, it's recommended to remove the default migrations generated by Laravel. These files are in `database/migrations/`.

### Install Wordpress

Go to /wp/wp-admin to setup your Wordpress project.

## Basic usage

Edit the default web route in your Laravel

```php
Route::get('/', function () {
    $post = Corcel\Model\Post::findOrFail(1);

    return view('welcome', compact('post'));
});
```

Replace the 'Laravel' heading in resources/views/welcome.blade.php by  {{ $post->title }}

Open your project in the browser, you will see 'Hello World!' as heading.

## References

* Read the docs of Corcel before you start: https://github.com/corcel/corcel


## Advanced Custom Fields

What makes Wordpress a real CMS? Right: Advanced Custom Fields. To implement this concept we use 2 packages

* The Advanced Custom Fields wordpress plugin
* The Corcel ACF plugin to fetch ACF data from a Corcel / Eloquent model.

The Corcel ACF plugin is a direct dependency of WP4Laravel and is automatically installed. To get the ACF 5 PRO plugin in Wordpress using composer, follow the instructions on https://github.com/PhilippBaschke/acf-pro-installer. Alternatively, if you don't have a license for ACF Pro, you can use the free version:

```bash
composer require wpackagist-plugin/advanced-custom-fields
```

Look at the docs of Corcel (https://github.com/corcel/acf) for the usage of Corcel with ACF.

## Wordpress configuration

Within /public/themes/wp4laravel/library you can update the Wordpress configuration. Most used for configuring post types and taxonomies. Every post type kan be defined in the directory post types. En example is already included. For taxonomies it works the same.

If you want to define your post types and taxonomies with a Wordpress plugin, thats no problem.

## Add plugins

Because Wordpress and his plugins are dependencies, you can only use plugins which are available with composer.

[WordPress Packagist](https://wpackagist.org) comes straight out of the box with WP4Laravel. It mirrors the WordPress [plugin](https://plugins.svn.wordpress.org) as a Composer repository.

### How do I use it?

Require the desired plugin using `wpackagist-plugin` as the vendor name.

```bash
composer require wpackagist-plugin/advanced-custom-fields
```

Plugins are installed to `public/plugins`.

Please visit [WordPress Packagist](https://wpackagist.org) website for more information and examples.

## Multilanguage
WP4Laravel contains various options to work with multilanguage-enabled websites. These solutions are based on using the free version of Poylang ([plugin](https://wordpress.org/plugins/polylang/), [wpackagist](https://wpackagist.org/search?q=polylang&type=any&search=)).

### Translatable models
A Translatable trait is included for working with the [Polylang](https://wordpress.org/plugins/polylang/) plugin. Include this trait in your models to gain access to useful properties for working with translated versions of posts.
```php
class Post extends \Corcel\Post
{
    use \WP4Laravel\Multilanguage\Translatable;
}
```

Including the trait with add a `language` scope for use with Eloquent and a `language` property.
```php
$posts = Post::language('de')->published()->first();
echo $post->language; // de
```

It also includes a `translations` property which yields a collection, keyed by the language code, of all available translations of a given post.
```php
$post = Post::slug('about-us')->first();
echo $post->translations['nl']->title; // Over ons
```

### Translatable taxonomies
Similarly to translating models, WP4Laravel also supports translating taxonomies. For this, you must enable the taxonomy to be translated in WP-Admin > Languages > Settings > Custom post Types and Taxonomies. To use the translation information on the website, create a model for the taxonomy and add the TranslatableTaxonomy-trait:
```php
class EventType extends \Corcel\Model\Taxonomy
{
    use \WP4Laravel\Multilanguage\TranslatableTaxonomy;

    protected $taxonomy = 'event_type';
}
```
The trait creates a scope on the model `language(string)` which can be used to filter terms:
```php
$language = localization()->getCurrentLocale();
$eventTypes = EventType::language()->get(); // Returns only the
```

### Making translatable menu's
The MenuBuilder has a utility function to work with menu's that have been translated using Polylang. First, configure your theme to have various menu locations. These are the slots on your website in which a menu is going to be displayed. Each entry has a location identifier and description:

```php
register_nav_menu('main', 'Main navigation in header');
register_nav_menu('contact', 'Contact links in menu dropdown and footer');
register_nav_menu('footer', 'Additional footer links');
```

Polylang will automatically make translated locations for every language you specify. Use the Wordpress admin interface to create a menu and assign it to a location. Than, call the `MenuBuilder::menuForLocation($slot, $language)` method call to find the appropriate menu for a location. It returns a basic `Corcel\Model\Menu` class. This method supports both translated and untranslated menu structures.

```php
// Get a untranslated menu
$menu = MenuBuilder::menuForLocation('main');

// Get a translated menu for a location
$menu = MenuBuilder::menuForLocation('main', Localization::getCurrentLanguage());
```

## Best practices

### Create your own models for each post type

If you want take advantage of the power of Eloquent, we advice to create a Laravel model for each of your post types.

```php
namespace App\Models;

use Corcel\Post as Corcel;

class Event extends Corcel
{
    protected $postType = 'event';
}
```

For example, you can add accessors to make life easier.

### Register your post types

When you access a post type from a specific model, you have to register this. You can do this to match a post_type with the dedicated model in the config/corcel.php file. For example:

config/corcel.php
```php
'post_types' => [
      'page' => \App\Models\Page::class,
      'faq' => \App\Models\Faq::class,
      'post' => \App\Models\Blog::class
],
```

If you choose to create a new class for your custom post type, you can have this class be returned for all instances of that post type.

### Catch-all your pages

First you have to make sure you define your catch all route at the bottom of your routes file:

Route:
```php
Route::get('{url}', 'PageController')->where('url', '(.*)')->name('page.show');
```

Create a PageController with the show method inside:

PageController:
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

/**
 * Catch all routes which are not defined in the routes file
 * Next search for a page which has the same url structure as the route
 * If not found,
 */
class PageController extends Controller
{
    public function show($url)
    {
        //  Get the post by url or abort
        $post = Page::url($url);

        //  Add post data to the site container
        app('site')->model($post);


        //  Show the template which is possibly chosen in WP
        return view($post->template);
    }
}

```

Page Model:
```php
<?php

namespace App\Models;

use WP4Laravel\Corcel\Pageurl;

/*
 * Model for WP pages
 */
class Page extends Post
{
    //  The Pageurl trait has a method to find a page based on the full url.
    use Pageurl;


    /**
     * What is the WP post type for this model?
     * @var string
     */
    protected $postType = 'page';
}
```

### Setup your homepage

> Note: This feature is available since version 0.7

Use a specific controller for your homepage or use the index method in your PageController for the homepage. Select in Wordpress in Settings/Reading the page which you want to use as Homepage.

```php
namespace App\Http\Controllers;

use App\Models\Page;

class HomeController extends Controller {

    public function __invoke()
    {
        /*
         * The Pageurl trait includes a homepage method wich get the ID of the page from the WP_options table
         */
        $post = Page::homepage();

        return view('home', compact('post'));
    }

}
```

### Get the url of a page

> Note: This feature is available since version 0.7

The Pageurl trait includes a getUrlAttribute method which generated the url of a page. This will include the path if a page has a parent.

```php
$page->url;
```


### Rendering \<picture\> tags
WP4Laravel includes a helper template and ViewProvider to correctly render \<picture\>-tags with crops, etc. This works correctly for both ThumbnailMeta and Image-classes.

#### Configuration
The included configuration file `config/picture.php` can be adapted to your project configuration. Copy the file to your project by executing:

```bash
php artisan vendor:publish --provider="WP4Laravel\WP4LaravelServiceProvider"
```
Note that this will copy the theme files, etc. as well. Change the included options to match your local URL, or the S3-setup. 

#### Usage
Crops must be named 'header_desktop_1x', 'header_mobile_2x', 'header_mobile_1x', 'header_mobile_14x' etc. Configure in Wordpress as follows:

```php
add_image_size('header_desktop_1x', 1000, 445, true);
add_image_size('header_desktop_2x', 2000, 890, true);
add_image_size('header_mobile_1x', 400, 400, true);
add_image_size('header_mobile_2x', 800, 800, true);
```

Use the following snippet in your blade views:
```blade
@include('wp4laravel::picture', [
    'picture' => $post->thumbnail,
    'breakpoints' => [
        '(min-width: 768px)' => 'header_desktop',
        '(max-width: 767px)' => 'header_mobile',
    ],
])
```

This results for example in the following output:
```html
<picture>
  <source srcset="/storage/kinderdijk-when-the-sky-is-on-fire-1000x445.jpg 1x, /storage/kinderdijk-when-the-sky-is-on-fire-2000x890.jpg 2x" media="(min-width: 768px)">
  <source srcset="/storage/kinderdijk-when-the-sky-is-on-fire-400x400.jpg 1x, /storage/kinderdijk-when-the-sky-is-on-fire-800x800.jpg 2x" media="(max-width: 767px)">
  <img src="/storage/kinderdijk-when-the-sky-is-on-fire.jpg" alt="Kinderdijk with a couple of windmills, viewed at dusk with a stunning red-orange sky">
</picture>
```

Configuring breakpoints is optional: not setting them will result in no <source> tags in
the output. **This is probably not what you want:** you should nearly always set at
least one breakpoint to utilize crops as responsive images. If you set only one
breakpoint, omit the media query:
```blade
@include('wp4laravel::picture', [
    'picture' => $post->thumbnail,
    'breakpoints' => ['header_desktop'],
])
```

Note that using multiple \<source\>-tags requires the use of media queries, so while the following example will generate output and not crash, the [W3 Validator](https://validator.w3.org/) will throw an error on your generated HTML. Don't do this.
```blade
@include('wp4laravel::picture', [
    'picture' => $post->thumbnail,
    'breakpoints' => ['header_desktop', 'header_mobile'],
])
```

#### Using ImageFake in the styleguide
Images are complex objects in the Wordpress/Corcel-environment, and mocking them is not trivial. This presents problems: if you design a component that renders some HTML and a inner image using `WP4Laravel\Picture`, you need to create a pretty complex structure to have all the necessary fields available.

For this use case, we offer a fake class: `WP4Laravel\ImageFake`. This fake class presents the necessary fields and methods to appear as valid option to WP4Laravel\Picture and can be used in the styleguide to render components that contain images. *Note that ImageFake is only suitable for use with Picture, as it is a partial fake.*

An example: suppose you have a component named "pretty_picture" that renders a single image + figure and caption. The component HTML looks like this:

```blade
<figure>
    @include('wp4laravel::picture', [
        'picture' => $image,
        'breakpoints' => ['pretty'],
    ])
    <figcaption>{{ $caption }}</figcaption>
</figure>
```

You can use this component on the website as such, where $block->fields->image is an instance of either ThumbnailMeta or Image, and $captionText is a string:

```blade
@include('components/blocks/gallery', [
    'image' => $block->fields->image,
    'caption' => $block->fields->captionText,
])
```

In the styleguide, you can use ImageFake instead:

```blade
@include('components/blocks/gallery', [
    'image' => \WP4Laravel\ImageFake::make([
        'full' => '/build/images/_temp/gallery-1.jpg',
        'pretty_220w' => '/build/images/_temp/gallery-1.jpg',
        'pretty_440w' => '/build/images/_temp/gallery-1@2x.jpg',
    ], 'Alt-text goes here'),
    'caption' => 'Such a pretty sight!',
])
```

The alt-text is optional. ImageFake requires that you always specify at least the 'full' option, as that is the case for real images too. The minimum data you'll need to add is thus:

```blade
\WP4Laravel\ImageFake::make([
    'full' => '/build/images/_temp/gallery-1.jpg',
]);
```

### Using the MenuBuilder to construct menus
WP4Laravel supplies a MenuBuilder utility class that can calculate the correct menu for you. You can use the class in a ViewComposer for example. This class correctly deals with using the custom title of a menu item or the post title when none is set.

The MenuBuilder class has a single public method `itemsIn($menu)` which returns a Collection of top-level items in the menu. Each entry has an `id` (int), `title` (string), `active` (whether this item should be "selected", boolean) and `url` (string) property. Additionally, each item has a `target` property (boolean). If set, you should open the link in a new tab. *Make sure to set rel=noopener too to prevent cross-site scripting and performance problems.*

This class supports a single level of nesting (two levels in total). Root-level items have a `children` (Collection) property with a list of their immediate child entries. Additionally, a root-level item has a boolean `childActive` property which is true if any of its children have the `active` flag set.

The MenuBuilder requires that your model has a `url` property that contains the canonical URL of an instance of the model.

#### Example usage
Add a URL property on your model. For example, when using a custom slug in the URL and a multilanguage-based setup:
```php
use App\Models\Traits\Translatable;

class Post extends \Corcel\Post
{
    protected $postType = 'post';
    protected $urlScope = 'nieuws';

    /**
     * Full URL to a post object
     * @return string
     */
    public function getUrlAttribute()
    {
        $url = '/' . $this->slug;

        // Prepend URL scope
        if (!empty($this->urlScope)) {
            $url = '/' . $this->urlScope . $url;
        }

        // Prepend the language if it's not the default language
        if (!empty($this->language) && $this->language !== config('app.supported-locales')[0]) {
            $url = '/' . $this->language . $url;
        }

        return $url;
    }
}

```

Add a view:
```blade
<nav>
    <ul class=menu>
        @foreach ($menu as $item)
            <li>
                <a class="{{ $item->active || $item->childActive ? 'active' : '' }}"
                    href="{{ $item->url }}" {!! $item->target ? 'target="_blank" rel=noopener' : '' !!}>
                    {{ $item->title }}
                </a>

                <ul class=submenu>
                    @foreach ($item->children as $child)
                        <li>
                            <a href="{{ $child->url }}" class="{{ $child->active ? 'active' : '' }}">
                                {{ $child->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</nav>
```

Add a ViewComposer for that view:
```php
<?php

namespace App\Http\ViewComposers;

use Corcel\Menu;
use Illuminate\Http\Request;
use Illuminate\View\View;
use WP4Laravel\MenuBuilder;

class Navigation
{
    private $builder;

    public function __construct(MenuBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function compose(View $view)
    {
        $menu = Menu::slug('main-menu')->first();
        $view->with('menu', $this->builder->itemsIn($menu));
    }
}
```

Alternatively, you can use the MenuBuilder-facade to gain a static interface:
```php
use WP4Laravel\Facades\MenuBuilder;

MenuBuilder::all();
```

### Translated menu's
The MenuBuilder has support for translating menu's in various languages. See [Multilanguage](#multilanguage) > [Making translatable menu's](#making-translatable-menus) for instructions.

### Activate WP preview function

> Note: This feature is available since version 0.7

With a few simple steps you can activate the preview function of Wordpress. Unfortunately the preview URL's are not secured!

* Make sure you have a named route for a detail page of each post type, like:

```php
Route::get('blog/{slug}', 'BlogController@show')->name('blog.show');
```

* Open config/corcel.php and append a preview array to map post types to named routes. Below an example:

```php
'preview' => [
    'post' => 'blog.show',
    'page' => 'page.show',
    'faq' => 'faq.show',
],
```

* When you want to preview a post which is not yet published, the Wordpress endpoint for the preview is your homepage added with some GET parameters. To catch these parameters you have to append a specific middleware to your homepage route. When the middleware matches a preview, it will redirect to the defined route of the post type, like 'blog.show'. As slug will be used "\_\_preview".

```php
Route::get('/', 'HomeController')->name('home')->middleware(WP4Laravel\Middleware\Preview::class);
```

>Note: You are free to implement the middleware on your own way, more info at the [Laravel Docs](https://laravel.com/docs/master/middleware)

* Append the Preview trait to you dedicated models:

```php
namespace App\Models;

use WP4Laravel\Corcel\Preview;

class Post extends \Corcel\Model\Post {
    use Preview;
}
```

* Make sure each model has a static method called current. This method will be used to select the current post based on the url. In most cases this method looks like:

```
public static function current($slug)
{
    return static::published()
            ->slug($url)
            ->firstOrFail();
}
```

Excepts for a page which can have a parent, use the following if your Page models uses the Pageurl trait.

```
public static function current($url)
{
    return static::url($url);
}
```

* In your controller use the static method publishedOrPreview method to get your current post or a preview.

```
public function show(Request $request, $slug)
{
    $post = Post::publishedOrPreview($request, $slug);

    return view($post->template, compact('post'));
}
```

### SEO tags for models
Creating the right SEO-tags depends on defining what the "primary" model instance is of this page. On the page of a news item, this is likely the news item itself. For an index page, you might want to create a specific page (with or without content) just so that you have place to configure Yoast.

The primary instance for a site is set on the Site. You usually do this in every controller action that renders a page.
```php
$post = Post::slug('new-team-update')->firstOrFail();
app('site')->model($post);
```
Add the Seo-trait to your models:
```php
class Post
{
    use \WP4Laravel\Corcel\Seo;
}
```
This trait adds a seo-attribute `$post->seo` which contains an array of all meta keys and their appropriate values. You can render all appropriate properties using:
```blade
@foreach ($site->get(‘seo’) as $name => $content)
    <meta name=“{{ $name }}” content=“{{ $content }}“>
@endforeach
```

### Hosting assets on S3
The Corcel libraries doesn't support media posts from external storage like an S3 bucket. This wrapper adds this support to get url's of the original files but also the url's of the generated thumbnails.

#### Requirements
* Laravel configured with S3 storage
* Wordpress configured with the S3 Offload plugin

#### Usage
Get the url of the featured image of a post

```php
\WP4Laravel\S3Media::handle($post->thumbnail)->url();
```

The same from the site container in a blade template

```blade
{{ $site->s3($post->thumbnail)->url() }}
```

Get the url of the 'large' crop from the media object

```blade
{{ $site->s3($post->thumbnail)->size('large') }}
```

Get the url of an ACF Image field

```blade
{{ $site->s3($post->acf->image('my_image_field'))->url() }}
```

Because of the main usage in a blade template, the S3Media object does not generate exceptions. If something is wrong (bad input, file not exists) the url() and site() methods just returns null.

### RSS-feeds
> This feature requires WP4Laravel 0.8.0 or later

WP4Laravel has built-in rudimentary support for generating RSS-feeds. Use as follows:
```php
return WP4Laravel\RSS::feed(Illuminate\Support\Collection $posts, string $title);
```
The first argument is a (Eloquent) Collection of all items you want included in your feed (note: these items must be
instances or subclasses of `Corcel\Model\Post`). The second parameter is the title included of the feed.


#### Example usage
Add a route in your `routes/web.php` file:
```php
Route::get('/news/feed.xml', 'NewsController@feed');
```

and add a method to your controller:
```php
class NewsController
{
    public function feed()
    {
        return \WP4Laravel\Facades\RSS::feed(News::all(), 'Alle nieuwsberichten');
    }
}
```

