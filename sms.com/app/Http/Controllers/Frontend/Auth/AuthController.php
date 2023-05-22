<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Frontend\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct() {
        parent::__construct();
        $this->pathResource = get_path_resource(__DIR__, __CLASS__);
        load_lang($this->pathResource);
    }

    public function showLogin() {
        if (Auth::guard('customer')->check() && Auth::guard('customer')->user()->status) {
            return redirect()->route('fe_dashboard_');
        }
        return load_view('auth.login');
    }

    public function doLogin(Request $request) {
        $login = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'status' => 1,
        ];
        $rules = [
            'email' => 'required|email',
            'password' => 'required|between:6,64',
        ];
        $message = [
            'email.required' => lang_trans('error_email_required'),
            'email.email'  => lang_trans('error_email_email'),
            'password.required' => lang_trans('error_password_required'),
            'password.between' => lang_trans('error_password_between'),
        ];

        $validator = Validator::make($login, $rules, $message);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            if(Auth::guard('customer')->attempt($login, true)) {
                return redirect()->route('fe_dashboard_');
            } else {
                session()->flash('error_email_password', lang_trans('error_email_password'));
                return redirect()->back()->withInput();
            }
        }
    }

    public function doLogout() {
        Auth::guard('customer')->logout();
        Session::flush();
        return redirect(site_url());
    }
}
