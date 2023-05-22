<?php

namespace App\Models\Backend\Logging;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class LoggingModel {
    private $tableLogging = 'logging';

    public function add($table, $action, $id = null) {
        $agent = new Agent();
        $userAgent = [
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
            'device ' => $agent->device(),
        ];

        $loggingType = config('main.logging_type');
        $content = $loggingType[$table][$action];
        if ($id) {
            $content .= ' :: id=' . $id;
        }
        $id = DB::table($this->tableLogging)->insertGetId([
            'username' => Auth::user()->username,
            'full_name' => Auth::user()->full_name,
            'content' => $content,
            'ip' => get_client_ip(),
            'user_agent' => serialize($userAgent),
            'created_at' => NOW(),
        ]);
        return $id;
    }
}
