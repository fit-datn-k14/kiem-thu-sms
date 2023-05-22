<?php

namespace App\Http\Controllers\Backend\Setting;

use App\Http\Controllers\Backend\Controller;
use App\Models\Backend\Setting\SettingModel;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller {
    private $settingModel;

    public function __construct(SettingModel $model) {
        parent::__construct();
        $this->pathResource = get_path_resource(__DIR__, __CLASS__);
        $this->settingModel = $model;
        load_lang($this->pathResource);
    }

    public function edit() {
        $validator = $this->validateForm();

        if ($validator->fails()) {
            flash_error(lang_trans('error_warning'));
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $this->settingModel->edit('config', request()->all());
            flash_success(lang_trans('text_success'));
            return redirect(site_url($this->pathResource));
        }
    }

    public function getForm() {
        $data = $this->breadcrumbs();

        $data['action'] = site_url($this->pathResource);

        $data['money_ranges'] = config('main.config_total_money_recharge');

        $attributes = [
            'config_name' => '',
            'config_address' => '',
            'config_email' => '',
            'config_money_recharge' => [],
            'config_limit_admin' => 35,
            'config_sms_price' => 1000,
            'config_telephone' => '',
            'config_meta_title' => '',
            'config_meta_description' => '',
            'config_meta_keyword' => '',
            'config_maintenance' => 0,
            'config_secure' => 0,
        ];
        foreach ($attributes as $name => $attribute) {
            if (Request::old($name)) {
                $data[$name] = Request::old($name);
            } elseif (config_get($name)) {
                $data[$name] = config_get($name);
            } else {
                $data[$name] = $attribute;
            }
        }

        return load_view($this->pathResource, $data);
    }

    protected function validateForm() {
        $rules = [
            'config_email' => 'required|email',
            'config_telephone' => 'required',
            'config_name' => 'required|between:3,255',
            'config_address' => 'required|between:3,512',
            'config_meta_title' => 'required',
            'config_limit_admin' => 'numeric|required',
            'config_sms_price' => 'numeric|required',
        ];

        $messages = [
            'config_email.required' => lang_trans('error_email'),
            'config_email.email' => lang_trans('error_email'),
            'config_telephone.required' => lang_trans('error_telephone'),
            'config_name.required' => lang_trans('error_name'),
            'config_name.between' => lang_trans('error_name'),
            'config_address.required' => lang_trans('error_address'),
            'config_address.between' => lang_trans('error_address'),
            'config_meta_title.required' => lang_trans('error_meta_title'),
            'config_limit_admin.numeric' => lang_trans('error_config_limit_admin'),
            'config_limit_admin.required' => lang_trans('error_config_limit_admin'),
            'config_sms_price.numeric' => lang_trans('error_config_sms_price'),
            'config_sms_price.required' => lang_trans('error_config_sms_price'),
        ];

        $validator = Validator::make(Request::all(), $rules, $messages);

        return $validator;
    }
}
