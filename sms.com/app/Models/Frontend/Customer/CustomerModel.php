<?php

namespace App\Models\Frontend\Customer;

use App\Models\Frontend\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;

class CustomerModel extends Model {
    private $tableRecharge = 'recharge';
    private $tableCustomer = 'customer';

    public function getListRecharge($data = []) {
        $customerId = Auth::guard('customer')->user()->id;

        $query = DB::table($this->tableRecharge);
        $orderByColumn = 'created_at';
        $orderByDirection = 'desc';

        if (isset($data['skip']) || isset($data['take'])) {
            if ($data['skip'] < 0) {
                $data['skip'] = 0;
            }

            if ($data['take'] < 1) {
                $data['take'] = 20;
            }
            $query = $query->skip($data['skip'])->take($data['take']);
        }

        $query = $query->where('customer_id', $customerId)->orderBy($orderByColumn, $orderByDirection)->get();
        return format_array($query);
    }

    public function getTotalRecharge() {
        $customerId = Auth::guard('customer')->user()->id;

        $query = DB::table($this->tableRecharge);
        $query = $query->where('customer_id', $customerId)->count('id');
        return $query;
    }

    public function getCurrentCustomer() {
        $customerId = Auth::guard('customer')->user()->id;
        $query = DB::table($this->tableCustomer)->where('id', $customerId)->first();
        return format_array($query);
    }

    public function editCurrentCustomer($data) {
        $customerId = Auth::guard('customer')->user()->id;

        $agent = new Agent();
        $userAgent = [
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
            'device ' => $agent->device(),
        ];

        DB::table($this->tableCustomer)->where('id', (int)$customerId)->update([
            'full_name' => $data['full_name'],
            // 'image' => $data['image'],
            'sms_prefix' => $data['sms_prefix'],
            'ip' => get_client_ip(),
            'user_agent' => serialize($userAgent),
            'updated_at' => NOW(),
        ]);

        if ($data['password']) {
            DB::table($this->tableCustomer)->where('id', (int)$customerId)->update([
                'password' => Hash::make($data['password']),
            ]);
        }
    }
}
