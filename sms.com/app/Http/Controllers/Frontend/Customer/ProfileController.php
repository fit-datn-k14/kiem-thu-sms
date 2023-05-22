<?php

namespace App\Http\Controllers\Frontend\Customer;

use App\Http\Controllers\Frontend\Controller;
use App\Models\Frontend\Customer\CustomerModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProfileController extends Controller
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

    public function edit(){
        $validator = $this->validateForm();
        if ($validator->fails()) {
            flash_error(lang_trans('error_warning'));
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $this->customerModel->editCurrentCustomer(Request::all());
            flash_success(lang_trans('text_success'));
            if (Request::input('_redirect') == 'edit') {
                $url = site_url($this->pathResource);
            } else {
                $url = site_url('common/dashboard');
            }
            return redirect()->intended($url);
        }
    }

    public function getForm(){
        $data = [];

        $data['action'] = site_url($this->pathResource);
        $data['back'] = site_url('common/dashboard');

        $customers = [
            'full_name' => '',
            'email' => '',
            'sms_prefix' => '',
        ];

        $info = $this->customerModel->getCurrentCustomer();

        foreach ($customers as $name => $customer) {
            if (Request::old($name)) {
                $data[$name] = Request::old($name);
            } elseif (!empty($info)) {
                $data[$name] = $info[$name];
            }
        }

        if (Request::old('password')) {
            $data['password'] = Request::old('password');
        } else {
            $data['password'] = '';
        }

        if (Request::old('password_re_enter')) {
            $data['password_re_enter'] = Request::old('password_re_enter');
        } else {
            $data['password_re_enter'] = '';
        }

        return load_view($this->pathResource . '_form', $data);
    }

    protected function validateForm(){
        $rules = [
            'full_name' => 'required|min:1|max:255',
            'password' => 'validate_password',
            'password_re_enter' => 'same:password',
            'sms_prefix' => 'max:96',
        ];

        $messages = [
            'full_name.required' => lang_trans('error_full_name'),
            'full_name.min' => lang_trans('error_full_name'),
            'full_name.max' => lang_trans('error_full_name'),
            'password.min' => lang_trans('error_password'),
            'password.max' => lang_trans('error_password'),
            'password.required' => lang_trans('error_password'),
            'password.validate_password' => lang_trans('error_password'),
            'password_re_enter.same' => lang_trans('error_password_same'),
            'sms_prefix.max' => lang_trans('error_sms_prefix'),
        ];
        $validator = Validator::make(Request::all(), $rules, $messages);
        $validator->addExtension('validate_password', function($attribute, $value, $parameters, $validator){
            if (!empty(Request::input('password')) &&
                ((Str::length(Request::input('password')) < 8) || (Str::length(Request::input('password')) > 64))) {
                return false;
            }
            return true;
        });
        return $validator;
    }
}
