<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::namespace('Frontend')->prefix(config('frontend.uri'))->group(function() {
    Route::get('/', function() {
        if (Auth::guard('customer')->check() && Auth::guard('customer')->user()->status) {
            return redirect()->route('fe_dashboard_');
        } else {
            return redirect(config('frontend.uri') . '/auth/login');
        }
    });

    // Auth
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function() {
        Route::get('login', 'AuthController@showLogin')->name('fe_login_');
        Route::post('login', 'AuthController@doLogin');

        /*Route::get('forgot-password', 'AuthController@showLogin')->name('fe_forgot_');
        Route::post('forgot-password', 'AuthController@doLogin');*/

        Route::get('logout', 'AuthController@doLogout');
    });

    Route::middleware(['AccessPermFrontend'])->group(function() {
        // Common
        Route::group(['namespace' => 'Common', 'prefix' => 'common'], function() {
            Route::get('dashboard', 'DashboardController@index')->name('fe_dashboard_');
        });

        // Contact
        Route::group(['namespace' => 'Contact', 'prefix' => 'contact'], function() {
            Route::group(['prefix' => 'contact'], function() {
                Route::get('/', 'ContactController@getList');
                Route::get('page/{pageNumber}', 'ContactController@getList');

                Route::get('add', 'ContactController@getForm');
                Route::post('add', 'ContactController@add');

                Route::get('edit/{id}', 'ContactController@getForm');
                Route::post('edit/{id}', 'ContactController@edit');

                Route::get('delete/{id}', 'ContactController@delete');
                Route::post('delete', 'ContactController@delete');

                Route::post('ajax-status', 'ContactController@ajaxStatus');
                Route::get('auto-complete', 'ContactController@autoComplete');
            });

            Route::group(['prefix' => 'sms-group'], function() {
                Route::get('/', 'SmsGroupController@getList');
                Route::get('page/{pageNumber}', 'SmsGroupController@getList');

                Route::post('send-sms', 'SmsGroupController@sendSms');
            });

            Route::group(['prefix' => 'sms-single'], function() {
                Route::get('/', 'SmsSingleController@getList');
                Route::get('page/{pageNumber}', 'SmsSingleController@getList');

                Route::post('send-sms', 'SmsSingleController@sendSms');

                Route::post('write-sms-single', 'SmsSingleController@writeSmsSingle');
            });

            Route::group(['prefix' => 'sms-history'], function() {
                Route::get('/', 'SmsHistoryController@getList');
                Route::get('page/{pageNumber}', 'SmsHistoryController@getList');

                Route::get('info/{id}', 'SmsHistoryController@getInfo');
                Route::get('info/{id}/page/{pageNumber}', 'SmsHistoryController@getInfo');

                Route::get('delete/{id}', 'SmsHistoryController@delete');
                Route::post('delete', 'SmsHistoryController@delete');
            });
        });

        // Customer
        Route::group(['namespace' => 'Customer', 'prefix' => 'customer'], function() {
            Route::group(['prefix' => 'recharge-history'], function() {
                Route::get('/', 'RechargeController@getList');
                Route::get('page/{pageNumber}', 'RechargeController@getList');
            });

            Route::group(['prefix' => 'profile'], function() {
                Route::get('/', 'ProfileController@getForm');
                Route::post('/', 'ProfileController@edit');
            });
        });
    });
});
