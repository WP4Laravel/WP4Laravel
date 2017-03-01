<?php

namespace App\Providers;

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
        $this->publishes([
            __DIR__.'../resources/theme' => public_path('themes/wp4laravel'),
            __DIR__.'../resources/wp-config.php' => public_path('wp-config.php'),
        ], 'public');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
