<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SmsHandleServiceProvider extends ServiceProvider
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
        $this->app->singleton('SmsHandle', function() {
            return $this->app->make('App\Libraries\SmsHandle');
        });
    }
}
