<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CoreController;

class Controller extends CoreController
{
    public function __construct() {
        parent::__construct();
        app('CommonHandle')->initResourceDir(config('backend.dir'));
        app('CommonHandle')->initBackendUri(config('backend.uri'));
    }
}
