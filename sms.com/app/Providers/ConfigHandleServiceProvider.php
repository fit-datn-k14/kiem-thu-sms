<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ConfigHandleServiceProvider extends ServiceProvider
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
        $this->app->singleton('ConfigHandle', function() {
            return $this->app->make('App\Libraries\ConfigHandle');
        });
    }
}
