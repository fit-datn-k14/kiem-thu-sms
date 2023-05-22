<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FlashHandleServiceProvider extends ServiceProvider
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
        $this->app->singleton('FlashHandle', function() {
            return $this->app->make('App\Libraries\FlashHandle');
        });
    }
}
