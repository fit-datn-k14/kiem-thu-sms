<?php

namespace App\Http\Controllers\Frontend\Customer;

use App\Http\Controllers\Frontend\Controller;
use App\Models\Frontend\Customer\CustomerModel;
use Illuminate\Support\Facades\Auth;

class RechargeController extends Controller
{
    private $customerModel;

    public function __construct(
        CustomerModel $customerModel
    ) {
        parent::__construct();
        $this->pathResource = get_path_resource(__DIR__, __CLASS__);
        load_lang($this->pathResource);
        $this->customerModel = $customerModel;
    }

    public function getList($page = null){
        $data = [];

        if (!isset($page)) {
            $page = 1;
        }
        $page = (int)$page;

        $filterData = [
            'skip' => ($page - 1) * config('frontend.page_limit'),
            'take' => config('frontend.page_limit'),
        ];

        $data['recharges'] = [];
        $total = $this->customerModel->getTotalRecharge();
        $results = $this->customerModel->getListRecharge($filterData);

        $orderNumber = 1;
        foreach ($results as $result) {
            $skip = ($page - 1) * config('frontend.page_limit');
            $data['recharges'][] = [
                'id' => $result['id'],
                'amount_paid' => $result['amount_paid'],
                'amount_received' => $result['amount_received'],
                'amount_total' => $result['amount_total'],
                'payment_method' => $result['payment_method'],
                'created_at' => date(config('main.datetime_format'), strtotime($result['created_at'])),
                'order_number' => $skip + $orderNumber,
            ];
            $orderNumber++;
        }

        $data['customer_money'] = $customerId = Auth::guard('customer')->user()->money;

        $data['pagination'] = $this->renderPaging($total, $page);
        return load_view($this->pathResource . '_list', $data);
    }
}
