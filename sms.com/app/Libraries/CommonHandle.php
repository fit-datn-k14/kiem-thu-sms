<?php

namespace App\Libraries;

use Illuminate\Contracts\View\Factory as ViewFactory;

class CommonHandle {
    private $siteType;
    private $uri;
    private $dir;

    // Load folder backend or frontend
    public function initResourceDir($siteType = null) {
        $this->siteType = $siteType;
    }

    // Load uri backend or frontend
    public function initBackendUri($uri = null) {
        $this->uri = $uri;
    }

    public function url($path = null, $parameters = [], $secure = null) {
        if (!is_null($this->uri)) {
            return url($this->uri . '/' . $path, $parameters, $secure);
        }
        return url($path . '/', $parameters, $secure);
    }

    public function homeUrl($path = null, $parameters = [], $secure = null) {
        return url($path . '/', $parameters, $secure);
    }

    public function asset($path, $secure = null) {
        if (!is_null($this->siteType)) {
            return asset($this->siteType . '/' . $path, $secure);
        }
        return asset($path, $secure);
    }

    function view($view = null, $data = [], $mergeData = []) {
        $factory = app(ViewFactory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        if (!is_null($this->siteType)) {
            $view = $this->siteType . '.' . str_replace('/', '.', $view);
        }

        return $factory->make($view, $data, $mergeData);
    }

    public function loadLangDir($dir = null) {
        $this->dir = $dir;
    }

    public function trans($key = null, $replace = [], $locale = null) {

        if (is_null($key)) {
            return app('translator');
        }

        // Check exit from dir language - Check exit from main language file - language_list.blade.php
        $fromDir = $fromMain = '';
        if (!is_null($this->siteType)) {
            $fromDir .= $this->siteType . '/';
            $fromMain .= $this->siteType . '/language.';
        }

        if (!is_null($this->dir)) {
            $fromDir .= $this->dir . '.';
        }

        if (!is_null($key)) {
            $fromDir .= $key;
            $fromMain .= $key;
        }

        if (app('translator')->has($fromDir)) {
            $keyResult = $fromDir;
        } elseif (app('translator')->has($fromMain)) {
            $keyResult = $fromMain;
        } else {
            $keyResult = $fromDir;
        }

        return app('translator')->get($keyResult, $replace, $locale);
    }
}
