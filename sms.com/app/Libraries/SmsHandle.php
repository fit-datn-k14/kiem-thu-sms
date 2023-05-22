<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Auth;

class SmsHandle {

    public function getBalance($smsConfig) {
        $obj = $this->curlGetBalance($smsConfig['apiKey'], $smsConfig['secretKey']);

        $data = [];
        if ($obj['CodeResponse'] == 100) {
            $data['success'] = true;
            $data['balances'] = format_currency($obj['Balance'], true);
        } elseif ($obj['CodeResponse'] == 99) {
            $data['msg'] = true;
            $data['balances'] = 'Lỗi không xác định , vui lòng thử lại sau - '.$obj['ErrorMessage'];
        } elseif ($obj['CodeResponse'] == 101) {
            $data['msg'] = true;
            $data['balances'] = 'Đăng nhập thất bại (api key hoặc secrect key không đúng ) - '.$obj['ErrorMessage'];
        } elseif ($obj['CodeResponse'] == 102) {
            $data['msg'] = true;
            $data['balances'] = 'Tài khoản đã bị khóa - '.$obj['ErrorMessage'];
        } elseif ($obj['CodeResponse'] == 103) {
            $data['msg'] = true;
            $data['balances'] = 'Số dư tài khoản không đủ dể gửi tin - '.$obj['ErrorMessage'];
        } elseif ($obj['CodeResponse'] == 104) {
            $data['msg'] = true;
            $data['balances'] = 'Mã Brandname không đúng - '.$obj['ErrorMessage'];
        } else {
            $data['msg'] = true;
            $data['balances'] = $obj['ErrorMessage'];
        }

        return $data;
    }

    public function sendSms($smsConfig, $phone, $content) {
        /*$brandName = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->sms_brand_name : null;
        $smsConfig = config('sms.esms_config');
        $smsConfig = [
            'apiKey' => $smsConfig['apiKey'],
            'secretKey' => $smsConfig['secretKey'],
            'smsType' => $smsConfig['smsType'],
            'isUnicode' => $smsConfig['isUnicode'],
            'brandName' => $brandName ?? $smsConfig['brandName'],
            'sandbox' => $smsConfig['sandbox'],
        ];*/
        // $obj = $this->curlExecSend($smsConfig['apiKey'], $smsConfig['secretKey'], $smsConfig['smsType'], $smsConfig['isUnicode'], $smsConfig['sandbox'], $phone, $content, $smsConfig['brandName']);
        $obj = [
            'CodeResult' => 100,
        ];
        return [
            'code_result' => $obj['CodeResult'],
            'error_message' => isset($obj['ErrorMessage']) ? $obj['ErrorMessage'] : '',
            'api_sms_id' => isset($obj['SMSID']) ? $obj['SMSID'] : '',
        ];
    }

    protected function curlGetBalance($ApiKey, $SecretKey){
        $data = "http://rest.esms.vn/MainService.svc/json/GetBalance/{$ApiKey}/{$SecretKey}";
        $curl = curl_init($data);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        curl_close($curl);
        return json_decode($result,true);
    }

    protected function curlExecSend($APIKey, $SecretKey, $SmsType, $IsUnicode, $Sandbox, $Phone, $Content, $Brandname = ''){
        if ($Brandname) {
            $URL_SEND = "http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?ApiKey={$APIKey}&SecretKey={$SecretKey}&SmsType={$SmsType}&IsUnicode={$IsUnicode}&Sandbox={$Sandbox}&Phone={$Phone}&Content={$Content}&Brandname={$Brandname}";
        } else {
            $URL_SEND = "http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?ApiKey={$APIKey}&SecretKey={$SecretKey}&SmsType={$SmsType}&IsUnicode={$IsUnicode}&Sandbox={$Sandbox}&Phone={$Phone}&Content={$Content}";
        }

        $curl = curl_init($URL_SEND);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $status = curl_getinfo($curl);
        $error = curl_error($curl);
        echo "<pre>";print_r($status);
        echo "<pre>";print_r($error);die;
        $result = curl_exec($curl);
        curl_close($curl);
        return json_decode($result,true);
    }
}
