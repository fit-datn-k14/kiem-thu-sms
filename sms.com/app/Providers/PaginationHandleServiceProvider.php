<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PaginationHandleServiceProvider extends ServiceProvider
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
        $this->app->singleton('PaginationHandle', function() {
            return $this->app->make('App\Libraries\PaginationHandle');
        });
    }
}
