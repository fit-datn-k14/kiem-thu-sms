<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->composerRegisterBackend();
        $this->composerRegisterFrontend();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->composerBackend('backend.layout');
        $this->composerFrontend('frontend.layout');
    }

    private function composerBackend($siteType){
        View::composer($siteType . '.header', 'App\Http\Controllers\Backend\Layout\HeaderController');
        View::composer($siteType . '.sidebar', 'App\Http\Controllers\Backend\Layout\SidebarController');
        View::composer($siteType . '.footer', 'App\Http\Controllers\Backend\Layout\FooterController');
    }

    private function composerRegisterBackend(){
        $this->app->singleton(\App\Http\Controllers\Backend\Layout\HeaderController::class);
        $this->app->singleton(\App\Http\Controllers\Backend\Layout\SidebarController::class);
        $this->app->singleton(\App\Http\Controllers\Backend\Layout\FooterController::class);
    }

    private function composerFrontend($siteType){
        View::composer($siteType . '.header', 'App\Http\Controllers\Frontend\Layout\HeaderController');
        View::composer($siteType . '.sidebar', 'App\Http\Controllers\Frontend\Layout\SidebarController');
        View::composer($siteType . '.footer', 'App\Http\Controllers\Frontend\Layout\FooterController');
    }

    private function composerRegisterFrontend(){
        $this->app->singleton(\App\Http\Controllers\Frontend\Layout\HeaderController::class);
        $this->app->singleton(\App\Http\Controllers\Frontend\Layout\SidebarController::class);
        $this->app->singleton(\App\Http\Controllers\Frontend\Layout\FooterController::class);
    }
}
