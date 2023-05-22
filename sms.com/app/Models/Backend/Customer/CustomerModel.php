<?php

namespace App\Models\Backend\Customer;

use App\Models\Backend\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;

class CustomerModel extends Model {
    private $tableCustomer = 'customer';
    private $tableCustomerGroup = 'customer_group';

    public function add($data) {
        $agent = new Agent();
        $userAgent = [
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
            'device ' => $agent->device(),
        ];

        $id = DB::table($this->tableCustomer)->insertGetId([
            'full_name' => $data['full_name'],
            'customer_group_id' => (int)$data['customer_group_id'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'image' => $data['image'],
            'sms_brand_name' => $data['sms_brand_name'],
            'sms_prefix' => $data['sms_prefix'],
            'status' => (int)$data['status'],
            'newsletter' => (int)$data['newsletter'],
            'money' => 0,
            'total_sms' => 0,
            'sms_price' => (int)$data['sms_price'],
            'ip' => get_client_ip(),
            'user_agent' => serialize($userAgent),
            'created_at' => NOW(),
            'updated_at' => NOW(),
        ]);
        $this->addLogging($this->tableCustomer, __FUNCTION__, $id);
        return $id;
    }

    public function edit($id, $data) {
        $agent = new Agent();
        $userAgent = [
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
            'device ' => $agent->device(),
        ];

        DB::table($this->tableCustomer)->where('id', (int)$id)->update([
            'full_name' => $data['full_name'],
            'customer_group_id' => (int)$data['customer_group_id'],
            'image' => $data['image'],
            'sms_brand_name' => $data['sms_brand_name'],
            'sms_prefix' => $data['sms_prefix'],
            'status' => (int)$data['status'],
            'newsletter' => (int)$data['newsletter'],
            'sms_price' => (int)$data['sms_price'],
            'ip' => get_client_ip(),
            'user_agent' => serialize($userAgent),
            'updated_at' => NOW(),
        ]);

        if ($data['password']) {
            DB::table($this->tableCustomer)->where('id', (int)$id)->update([
                'password' => Hash::make($data['password']),
            ]);
        }
        $this->addLogging($this->tableCustomer, __FUNCTION__, $id);
    }

    public function delete($id) {
        DB::table($this->tableCustomer)->where('id', (int)$id)->delete();
        $this->addLogging($this->tableCustomer, __FUNCTION__, $id);
    }

    public function getById($id) {
        $query = DB::table($this->tableCustomer)->where('id', (int)$id)->first();
        return format_array($query);
    }

    public function getList($data = []) {
        $query = DB::table($this->tableCustomer)
            ->join($this->tableCustomerGroup, $this->tableCustomer . '.customer_group_id', '=', $this->tableCustomerGroup . '.id')
            ->select($this->tableCustomer . '.*', $this->tableCustomerGroup . '.name as customer_group');

        $where = [];
        if (!empty($data['filter_full_name'])) {
            $where[] = [$this->tableCustomer . '.full_name', 'like', '%' . $data['filter_full_name'] . '%'];
        }
        if (!empty($data['filter_email'])) {
            $where[] = [$this->tableCustomer . '.email', 'like', '%' . $data['filter_email'] . '%'];
        }
        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $where[] = [$this->tableCustomer . '.status', '=', (int)$data['filter_status']];
        }
        if (!empty($data['filter_customer_group_id'])) {
            $where[] = [$this->tableCustomerGroup . '.id', '=', (int)$data['filter_customer_group_id']];
        }

        $sortData = ['full_name', 'status', 'email', 'created_at', 'total_sms', 'sms_price', 'money'];
        $orderByColumn = 'created_at';
        if (isset($data['sort']) && in_array($data['sort'], $sortData)) {
            $orderByColumn = $data['sort'];
        }
        $orderByDirection = 'asc';
        if (isset($data['order']) && (strtolower($data['order']) == 'desc')) {
            $orderByDirection = 'desc';
        }

        if (isset($data['skip']) || isset($data['take'])) {
            if ($data['skip'] < 0) {
                $data['skip'] = 0;
            }

            if ($data['take'] < 1) {
                $data['take'] = 20;
            }
            $query = $query->skip($data['skip'])->take($data['take']);
        }

        $query = $query->where($where)->orderBy($orderByColumn, $orderByDirection)->get();
        return format_array($query);
    }

    public function getTotal() {
        $query = DB::table($this->tableCustomer)
            ->join($this->tableCustomerGroup, $this->tableCustomer . '.customer_group_id', '=', $this->tableCustomerGroup . '.id')
            ->select($this->tableCustomer . '.id');

        $where = [];
        if (!empty($data['filter_full_name'])) {
            $where[] = [$this->tableCustomer . '.full_name', 'like', '%' . $data['filter_full_name'] . '%'];
        }
        if (!empty($data['filter_email'])) {
            $where[] = [$this->tableCustomer . '.email', 'like', '%' . $data['filter_email'] . '%'];
        }
        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $where[] = [$this->tableCustomer . '.status', '=', (int)$data['filter_status']];
        }
        if (!empty($data['filter_customer_group_id'])) {
            $where[] = [$this->tableCustomerGroup . '.id', '=', (int)$data['filter_customer_group_id']];
        }

        $query = $query->where($where)->count($this->tableCustomer . '.id');
        return $query;
    }

    public function getTotalByGroupId($groupId) {
        $query = DB::table($this->tableCustomer)->where('customer_group_id', (int)$groupId)->count('id');
        return $query;
    }

    public function updateStatus($id, $status) {
        DB::table($this->tableCustomer)->where('id', (int)$id)->update([
            'status' => $status,
            'updated_at' => NOW(),
        ]);
    }

    public function updateMoney($id, $amount) {
        DB::table($this->tableCustomer)->where('id', (int)$id)->update([
            'money' => (float)$amount,
            'updated_at' => NOW(),
        ]);
    }
}
