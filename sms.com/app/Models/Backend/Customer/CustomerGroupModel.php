<?php

namespace App\Models\Backend\Customer;

use App\Models\Backend\Model;
use Illuminate\Support\Facades\DB;

class CustomerGroupModel extends Model {
    private $tableCustomerGroup = 'customer_group';

    public function add($data) {
        $id = DB::table($this->tableCustomerGroup)->insertGetId([
            'name' => $data['name'],
            'sort_order' => (int)$data['sort_order'],
        ]);
        $this->addLogging($this->tableCustomerGroup, __FUNCTION__, $id);
        return $id;
    }

    public function edit($id, $data) {
        DB::table($this->tableCustomerGroup)->where('id', (int)$id)->update([
            'name' => $data['name'],
            'sort_order' => (int)$data['sort_order'],
        ]);
        $this->addLogging($this->tableCustomerGroup, __FUNCTION__, $id);
    }

    public function delete($id) {
        DB::table($this->tableCustomerGroup)->where('id', (int)$id)->delete();
        $this->addLogging($this->tableCustomerGroup, __FUNCTION__, $id);
    }

    public function getById($id) {
        $query = DB::table($this->tableCustomerGroup)->where('id', (int)$id)->first();
        return format_array($query);
    }

    public function getList($data = []) {
        $query = DB::table($this->tableCustomerGroup);

        $sortData = ['name', 'sort_order'];
        $orderByColumn = 'name';
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

        $query = $query->orderBy($orderByColumn, $orderByDirection)->get();
        return format_array($query);
    }

    public function getTotal() {
        $query = DB::table($this->tableCustomerGroup)->count('id');
        return $query;
    }
}
