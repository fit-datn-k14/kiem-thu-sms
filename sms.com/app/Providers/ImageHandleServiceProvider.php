<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ImageHandleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('ImageHandle', function() {
            return $this->app->make('App\Libraries\ImageHandle');
        });
    }
}
