<?php

namespace App\Models\Frontend\Contact;

use App\Models\Frontend\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContactModel extends Model {
    private $tableContact = 'contact';

    public function add($data) {
        $customerId = Auth::guard('customer')->user()->id;
        $status = isset($data['status']) ? (int)$data['status'] : 0;
        $id = DB::table($this->tableContact)->insertGetId([
            'customer_id' => $customerId,
            'full_name' => $data['full_name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'sort_order' => (int)$data['sort_order'],
            'note' => $data['note'],
            'status' => $status,
            'created_at' => NOW(),
            'updated_at' => NOW(),
        ]);
        return $id;
    }

    public function edit($id, $data) {
        $customerId = Auth::guard('customer')->user()->id;
        $status = isset($data['status']) ? (int)$data['status'] : 0;
        DB::table($this->tableContact)->where([
            ['id', '=', (int)$id],
            ['customer_id', '=', $customerId],
        ])->update([
            'full_name' => $data['full_name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'sort_order' => (int)$data['sort_order'],
            'note' => $data['note'],
            'status' => $status,
            'updated_at' => NOW(),
        ]);
    }

    public function delete($id) {
        $customerId = Auth::guard('customer')->user()->id;
        DB::table($this->tableContact)->where([
            ['id', '=', (int)$id],
            ['customer_id', '=', $customerId],
        ])->delete();
    }

    public function getById($id) {
        $customerId = Auth::guard('customer')->user()->id;
        $query = DB::table($this->tableContact)->where([
            ['id', '=', (int)$id],
            ['customer_id', '=', $customerId],
        ])->first();
        return format_array($query);
    }

    public function getList($data = []) {
        $customerId = Auth::guard('customer')->user()->id;
        $query = DB::table($this->tableContact);

        $where = [
            ['customer_id', '=', $customerId],
        ];
        if (!empty($data['filter_full_name'])) {
            $where[] = ['full_name', 'like', '%' . $data['filter_full_name'] . '%'];
        }
        if (!empty($data['filter_phone'])) {
            $where[] = ['phone', 'like', '%' . $data['filter_phone'] . '%'];
        }
        if (!empty($data['filter_address'])) {
            $where[] = ['address', 'like', '%' . $data['filter_address'] . '%'];
        }
        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $where[] = ['status', '=', (int)$data['filter_status']];
        }

        $sortData = ['full_name', 'phone', 'sort_order'];
        $orderByColumn = 'full_name';
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

    public function getTotal($data = []) {
        $customerId = Auth::guard('customer')->user()->id;

        $where = [
            ['customer_id', '=', $customerId],
        ];
        if (!empty($data['filter_full_name'])) {
            $where[] = ['full_name', 'like', '%' . $data['filter_full_name'] . '%'];
        }
        if (!empty($data['filter_phone'])) {
            $where[] = ['phone', 'like', '%' . $data['filter_phone'] . '%'];
        }
        if (!empty($data['filter_address'])) {
            $where[] = ['address', 'like', '%' . $data['filter_address'] . '%'];
        }
        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $where[] = ['status', '=', (int)$data['filter_status']];
        }

        $query = DB::table($this->tableContact)->where($where)->count('id');
        return $query;
    }

    public function updateStatus($id, $status) {
        $customerId = Auth::guard('customer')->user()->id;
        DB::table($this->tableContact)->where([
            ['id', '=', (int)$id],
            ['customer_id', '=', $customerId],
        ])->update([
            'status' => $status,
            'updated_at' => NOW(),
        ]);
    }
}
