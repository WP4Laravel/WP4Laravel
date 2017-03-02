<?php

namespace WP4Laravel;

use Illuminate\Support\ServiceProvider;

class WP4LaravelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::share('site', $this->app->make('site'));


        $this->mergeConfigFrom(
            __DIR__.'/../resources/config/site.php', 'site'
        );

        $this->publishes([
            __DIR__.'/../resources/theme' => public_path('themes/wp4laravel'),
            __DIR__.'/../resources/wp-config.php' => public_path('wp-config.php'),
            __DIR__.'/../resources/config/site.php' => config_path('site.php'),
        ], 'public');
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
