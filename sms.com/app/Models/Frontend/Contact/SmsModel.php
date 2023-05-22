<?php

namespace App\Models\Frontend\Contact;

use App\Models\Frontend\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SmsModel extends Model {
    private $tableContact = 'contact';
    private $tableCustomer = 'customer';
    private $tableSms = 'sms';
    private $tableSmsLog = 'sms_log';
    private $tableSmsSingle = 'sms_single';

    public function addSms($data, $isSingle = false) {
        $customerId = Auth::guard('customer')->user()->id;
        $id = DB::table($this->tableSms)->insertGetId([
            'customer_id' => $customerId,
            'total_sms' => $data['total_sms'],
            'total_success' => $data['total_success'],
            'total_fail' => $data['total_fail'],
            'created_at' => NOW(),
        ]);

        // Sms log
        if (count($data['contacts'])) {
            foreach ($data['contacts'] as $contact) {
                DB::table($this->tableSmsLog)->insert([
                    'sms_id' => $id,
                    'customer_id' => $customerId,
                    'contact_id' => $contact['contact_id'],
                    'total_sms' => $contact['total_sms'],
                    'api_sms_id' => $contact['api_sms_id'],
                    'is_success' => $contact['is_success'],
                    'phone' => $contact['phone'],
                    'msg' => $contact['msg'],
                    'content' => $contact['content'],
                ]);

                // Update sms single is sent
                if ($isSingle) {
                    $where = [
                        ['customer_id', '=', $customerId],
                        ['contact_id', '=', $contact['contact_id']],
                        ['total_sms', '>', 0],
                        ['date_write', '=', DB::raw('DATE(NOW())')],
                    ];
                    DB::table($this->tableSmsSingle)->where($where)->update([
                        'is_sent' => 1,
                    ]);
                }
            }
        }

        if ($data['total_success']) {
            // Update customer money
            $smsPrice = $data['sms_price'];
            $moneyUsed = $data['total_success'] * $smsPrice;

            DB::table($this->tableCustomer)->where('id', (int)$customerId)->update([
                'money' => DB::raw('money - ' . (float)$moneyUsed),
                'total_sms' => DB::raw('total_sms + ' . (int)$data['total_success']),
                'updated_at' => NOW(),
            ]);
        }

        return $id;
    }

    public function getContactPhone($id) {
        $customerId = Auth::guard('customer')->user()->id;
        $query = DB::table($this->tableContact)->select('phone')->where([
            ['id', '=', (int)$id],
            ['customer_id', '=', $customerId],
        ])->first();
        return isset($query->phone) ? $query->phone : '';
    }

    public function delete($id) {
        $customerId = Auth::guard('customer')->user()->id;
        DB::table($this->tableSms)->where([
            ['id', '=', (int)$id],
            ['customer_id', '=', $customerId],
        ])->delete();

        DB::table($this->tableSmsLog)->where([
            ['sms_id', '=', (int)$id],
            ['customer_id', '=', $customerId],
        ])->delete();
    }

    public function getList($data = []) {
        $customerId = Auth::guard('customer')->user()->id;
        $query = DB::table($this->tableSms);

        $where = [
            ['customer_id', '=', $customerId],
        ];

        $sortData = ['created_at'];
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

    public function getTotal($data = []) {
        $customerId = Auth::guard('customer')->user()->id;

        $where = [
            ['customer_id', '=', $customerId],
        ];

        $query = DB::table($this->tableSms)->where($where)->count('id');
        return $query;
    }

    public function getListSmsLog($smsId, $data = []) {
        $customerId = Auth::guard('customer')->user()->id;
        $query = DB::table($this->tableSmsLog);
        $query->leftJoin($this->tableContact, $this->tableSmsLog . '.contact_id', '=', $this->tableContact . '.id');

        $where = [
            [$this->tableSmsLog . '.customer_id', '=', $customerId],
            [$this->tableSmsLog . '.sms_id', '=', $smsId],
        ];

        $sortData = [$this->tableSmsLog . '.id'];
        $orderByColumn = $this->tableSmsLog . '.id';
        if (isset($data['sort']) && in_array($data['sort'], $sortData)) {
            $orderByColumn = $data['sort'];
        }
        $orderByDirection = 'desc';
        if (isset($data['order']) && (strtolower($data['order']) == 'asc')) {
            $orderByDirection = 'asc';
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

    public function getTotalSmsLog($smsId, $data = []) {
        $customerId = Auth::guard('customer')->user()->id;

        $where = [
            ['customer_id', '=', $customerId],
            ['sms_id', '=', $smsId],
        ];

        $query = DB::table($this->tableSmsLog)->where($where)->count('id');
        return $query;
    }

    // SMS Single
    public function writeSmsContentSingle($isAdd, $data) {
        $customerId = Auth::guard('customer')->user()->id;

        $query = DB::table($this->tableSmsSingle);
        if ($isAdd) {
            $query->insert([
                'customer_id' => $customerId,
                'contact_id' => (int)$data['contact_id'],
                'content' => $data['content'],
                'total_sms' => (int)$data['total_sms'],
                'is_sent' => false,
                'date_write' => DATE(NOW()),
            ]);
        } else {
            $query->where([
                ['customer_id', '=', $customerId],
                ['contact_id', '=', (int)$data['contact_id']],
            ])->update([
                'content' => $data['content'],
                'total_sms' => (int)$data['total_sms'],
                'date_write' => DATE(NOW()),
            ]);
        }
    }

    public function getSmsSingleByDate($contactId) {
        $customerId = Auth::guard('customer')->user()->id;

        $where = [
            ['customer_id', '=', $customerId],
            ['contact_id', '=', (int)$contactId],
            ['date_write', '=', DB::raw('DATE(NOW())')],
        ];

        $query = DB::table($this->tableSmsSingle)->where($where)->first();
        return format_array($query);
    }

    public function getSmsTotalSingle($contactIds) {
        $customerId = Auth::guard('customer')->user()->id;

        $where = [
            ['customer_id', '=', $customerId],
            ['total_sms', '>', 0],
            ['date_write', '=', DB::raw('DATE(NOW())')],
        ];

        $query = DB::table($this->tableSmsSingle)->where($where)->whereIn('contact_id', $contactIds)
            ->select('total_sms')->sum('total_sms');
        return $query;
    }

    public function getSmsSingles($contactIds) {
        $customerId = Auth::guard('customer')->user()->id;

        $where = [
            ['customer_id', '=', $customerId],
            ['total_sms', '>', 0],
            ['date_write', '=', DB::raw('DATE(NOW())')],
        ];

        $query = DB::table($this->tableSmsSingle)->where($where)->whereIn('contact_id', $contactIds)->get();
        return format_array($query);
    }
}
