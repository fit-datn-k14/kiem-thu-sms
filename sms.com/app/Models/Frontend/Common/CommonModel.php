<?php

namespace App\Models\Frontend\Common;

use App\Models\Frontend\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommonModel extends Model {
    private $tableContact = 'contact';
    private $tableSms = 'sms';

    public function getTotalContact() {
        $customerId = Auth::guard('customer')->user()->id;
        $query = DB::table($this->tableContact)->where([
            ['customer_id', '=', $customerId],
        ])->count('id');
        return $query;
    }

    public function getTotalContactActive() {
        $customerId = Auth::guard('customer')->user()->id;
        $query = DB::table($this->tableContact)->where([
            ['customer_id', '=', $customerId],
            ['status', '=', 1],
        ])->count('id');
        return $query;
    }

    public function statisticSmsSent($data) {
        $customerId = Auth::guard('customer')->user()->id;

        $from = $data['filter_from'] . ' 00:00:00';
        $to = $data['filter_to'] . ' 23:59:59';
        $query = DB::table($this->tableSms)->where([
            ['customer_id', '=', $customerId],
            ['created_at', '>=', $from],
            ['created_at', '<=', $to],
        ])->orderBy('created_at', 'asc')->get();
        return format_array($query);
    }
}
