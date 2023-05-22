<?php

namespace App\Models\Backend\Customer;

use App\Models\Backend\Model;
use App\Models\Backend\Logging\LoggingModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Exception;

class RechargeModel extends Model {
    private $tableRecharge = 'recharge';
    private $customerModel;

    public function __construct(LoggingModel $loggingModel, CustomerModel $customerModel){
        parent::__construct($loggingModel);
        $this->customerModel = $customerModel;
    }

    public function add($data) {
        $agent = new Agent();
        $userAgent = [
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
            'device ' => $agent->device(),
        ];

        $customer = $this->customerModel->getById((int)$data['customer_id']);

        if ($customer) {
            $amountPaid = (float)$data['amount_paid'];

            $moneyRechargeConfigList = config_get('config_money_recharge') ?? [];
            $rechargeConfigs = array_filter($moneyRechargeConfigList);
            $rechargeConfigKeys = array_keys($rechargeConfigs);

            $amountReceived = 0;
            $amountTotal = (float)$customer['money'];
            if ($rechargeConfigs && $amountPaid >= $rechargeConfigKeys[0]) {
                if ($amountPaid >= $rechargeConfigKeys[count($rechargeConfigKeys) - 1]) {
                    $total = $amountPaid + ($amountPaid * $rechargeConfigs[$rechargeConfigKeys[count($rechargeConfigKeys) - 1]] / 100);
                    $amountReceived = $total;
                    $amountTotal = $amountTotal + $total;
                } else {
                    $indexFind = -1;
                    $i = 0;
                    foreach ($rechargeConfigKeys as $moneyNumber) {
                        if ((float)$moneyNumber > $amountPaid) {
                            $indexFind = $i;
                            break;
                        }
                        $i++;
                    }
                    if ($indexFind > -1) {
                        $total = $amountPaid + ($amountPaid * $rechargeConfigs[$rechargeConfigKeys[$indexFind - 1]] / 100);
                        $amountReceived = $total;
                        $amountTotal = $amountTotal + $total;
                    } else {
                        $amountReceived = $amountPaid;
                        $amountTotal = $amountTotal + $amountPaid;
                    }
                }
            } else {
                $amountReceived = $amountPaid;
                $amountTotal = (float)$customer['money'] + $amountPaid;
            }

            $paymentMethod = config('main.payment_method');

            DB::beginTransaction();
            try {
                $id = DB::table($this->tableRecharge)->insertGetId([
                    'customer_id' => (int)$data['customer_id'],
                    'full_name' => $customer['full_name'],
                    'email' => $customer['email'],
                    'amount_paid' => $amountPaid,
                    'amount_received' => $amountReceived,
                    'amount_total' => $amountTotal,
                    'payment_method' => $paymentMethod['admin'] . Auth::user()->username . ' (' . Auth::user()->full_name . ')',
                    'ip' => get_client_ip(),
                    'user_agent' => serialize($userAgent),
                    'created_at' => NOW(),
                ]);
                $this->customerModel->updateMoney($data['customer_id'], $amountTotal);
                DB::commit();
                $this->addLogging($this->tableRecharge, __FUNCTION__, $id);
                return $id;
            } catch (Exception $e) {
                DB::rollBack();
                throw new Exception($e->getMessage());
            }
        }
        return null;
    }

    public function delete($id) {
        DB::table($this->tableRecharge)->where('id', (int)$id)->delete();
        $this->addLogging($this->customerModel, __FUNCTION__, $id);
    }

    public function getById($id) {
        $query = DB::table($this->tableRecharge)->where('id', (int)$id)->first();
        return format_array($query);
    }

    public function getList($data = []) {
        $query = DB::table($this->tableRecharge);

        $where = [];
        if (!empty($data['filter_full_name'])) {
            $where[] = ['full_name', 'like', '%' . $data['filter_full_name'] . '%'];
        }
        if (!empty($data['filter_email'])) {
            $where[] = ['email', 'like', '%' . $data['filter_email'] . '%'];
        }

        $sortData = ['full_name', 'email', 'created_at'];
        $orderByColumn = 'created_at';
        if (isset($data['sort']) && in_array($data['sort'], $sortData)) {
            $orderByColumn = $data['sort'];
        }
        $orderByDirection = 'desc';
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
        $query = DB::table($this->tableRecharge);

        $where = [];
        if (!empty($data['filter_full_name'])) {
            $where[] = ['full_name', 'like', '%' . $data['filter_full_name'] . '%'];
        }
        if (!empty($data['filter_email'])) {
            $where[] = ['email', 'like', '%' . $data['filter_email'] . '%'];
        }
        $query = $query->where($where)->count('id');
        return $query;
    }
}
