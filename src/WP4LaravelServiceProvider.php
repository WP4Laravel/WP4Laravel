<?php

namespace WP4Laravel;

use Illuminate\Support\ServiceProvider;
use View;

class WP4LaravelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../resources/config/site.php', 'site'
        );

        $this->publishes([
            __DIR__.'/../resources/theme' => public_path('themes/wp4laravel'),
            __DIR__.'/../resources/wp-config.php' => public_path('wp-config.php'),
            __DIR__.'/../resources/config/site.php' => config_path('site.php'),
        ], 'public');

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
    }
}
