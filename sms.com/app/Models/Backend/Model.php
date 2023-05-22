<?php

namespace App\Models\Backend;
use App\Models\Backend\Logging\LoggingModel;

class Model {
    private $loggingModel;

    public function __construct(LoggingModel $loggingModel = null) {
        $this->loggingModel = $loggingModel;
    }

    protected function addLogging($table, $action, $id = null) {
        $this->loggingModel->add($table, $action, $id);
    }
}
