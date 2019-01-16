<?php

namespace WP4Laravel;

use Illuminate\Support\ServiceProvider;
use View;
use WP4Laravel\Corcel\Picture;

class WP4LaravelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../resources/config/site.php', 'site');
        $this->mergeConfigFrom(__DIR__ . '/../resources/config/picture.php', 'picture');

        $this->publishes([
            __DIR__ . '/../resources/theme' => public_path('themes/wp4laravel'),
            __DIR__ . '/../resources/wp-config.php' => public_path('wp-config.php'),
        ], 'public');

        $this->publishes([
            __DIR__ . '/../resources/config/site.php' => config_path('site.php'),
            __DIR__ . '/../resources/config/picture.php' => config_path('picture.php'),
        ], 'config');

        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'wp4laravel');
        View::composer('wp4laravel::picture', Picture::class);

        View::share('site', $this->app->make('site'));
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('site', function ($app) {
            return new Site();
        });

        $this->app->singleton('wp4laravel::menubuilder', \WP4Laravel\MenuBuilder::class);
        $this->app->singleton('wp4laravel::rss', \WP4Laravel\RSS::class);

        $this->commands([
            \WP4Laravel\Commands\WPURLReplace::class
        ]);
    }
}
