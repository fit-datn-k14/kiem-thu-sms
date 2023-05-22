<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\CoreController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Controller extends CoreController
{
    public function __construct() {
        parent::__construct();
        app('CommonHandle')->initResourceDir(config('frontend.dir'));
    }

    protected function renderPaging($total, $page, $query = null, $limit = null, $paginate = null) {
        if (is_null($paginate)) {
            $paginate = [
                'total' => $total,
                'page' => $page,
                'limit' => $limit ? $limit : config('frontend.page_limit'),
                'url' => site_url($this->pathResource) . '/page/{page}' . ($query ? '?' . $query : ''),
            ];
        }

        pagination_init($paginate);
        return pagination_render();
    }

    protected function getSmsConfig() {
        $smsConfig = [];
        $money = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->money : 0;
        $smsPrefix = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->sms_prefix : '';
        $smsPriceCustomer = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->sms_price : 0;
        $totalSmsCustomer = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->total_sms : 0;

        $smsConfig['customer_money'] = $money;
        $smsConfig['customer_sms_prefix'] = $smsPrefix;
        $smsConfig['customer_total_sms_sent'] = $totalSmsCustomer;

        $smsConfig['sms_prefix_length'] = Str::length($smsPrefix) ? (Str::length($smsPrefix) + 1) : 0; // 1: space

        $smsPrice = $smsPriceCustomer ? (float)$smsPriceCustomer : (float)config_get('config_sms_price');
        $smsConfig['sms_price'] = $smsPrice;
        $smsConfig['total_sms_remain'] = floor($money / $smsPrice);

        return $smsConfig;
    }

    protected function getSmsApiConfig() {
        $brandName = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->sms_brand_name : null;
        $smsConfig = config('sms.esms_config');

        $smsApiConfig = [
            'apiKey' => $smsConfig['apiKey'],
            'secretKey' => $smsConfig['secretKey'],
            'smsType' => $smsConfig['smsType'],
            'isUnicode' => $smsConfig['isUnicode'],
            'brandName' => $smsConfig ? $brandName : $smsConfig['brandName'],
            'sandbox' => $smsConfig['sandbox'],
        ];

        return $smsApiConfig;
    }
}
