<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Backend\Controller;
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
        if (Auth::check()) {
            return redirect()->route('dashboard_');
        }
        return load_view('auth.login');
    }

    public function doLogin(Request $request) {
        $login = [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'status' => 1,
        ];
        $rules = [
            'username' => 'required|between:4,64|regex:/[a-zA-z0-9]/',
            'password' => 'required|between:5,64',
        ];
        $message = [
            'username.required' => lang_trans('error_username_required'),
            'username.between'  => lang_trans('error_username_between'),
            'username.regex'  => lang_trans('error_username_regex'),
            'password.required' => lang_trans('error_password_required'),
            'password.between' => lang_trans('error_password_between'),
        ];

        $validator = Validator::make($login, $rules, $message);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            if(Auth::attempt($login, true)) {
                return redirect()->route('dashboard_');
            } else {
                session()->flash('error_username_password', lang_trans('error_username_password'));
                return redirect()->back()->withInput();
            }
        }
    }

    public function doLogout() {
        Auth::logout();
        Session::flush();
        return redirect(site_url());
    }
}
