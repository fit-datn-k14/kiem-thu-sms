<?php

namespace App\Models\Frontend;
use App\Models\Backend\Logging\LoggingModel;
use Illuminate\Support\Facades\Auth;

class Model {
    private $loggingModel;

    public function __construct(LoggingModel $loggingModel = null) {
        $this->loggingModel = $loggingModel;
    }

    protected function addLogging($table, $action, $id = null) {
        $this->loggingModel->add($table, $action, $id);
    }
}
