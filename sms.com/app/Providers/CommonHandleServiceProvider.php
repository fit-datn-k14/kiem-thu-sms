<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CommonHandleServiceProvider extends ServiceProvider
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
        $this->app->singleton('CommonHandle', function() {
            return $this->app->make('App\Libraries\CommonHandle');
        });
    }
}
