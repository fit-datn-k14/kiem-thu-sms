<?php

namespace App\Http\Controllers\Backend\Common;

use App\Http\Controllers\Backend\Controller;

class DashboardController extends Controller
{
    public function __construct() {
        parent::__construct();
        $this->pathResource = get_path_resource(__DIR__, __CLASS__);
        load_lang($this->pathResource);
    }

    public function index() {
        $data = $this->breadcrumbs();
        return load_view($this->pathResource, $data);
    }
}
