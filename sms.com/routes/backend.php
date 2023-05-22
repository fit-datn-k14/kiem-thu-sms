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

Route::namespace('Backend')->prefix(config('backend.uri'))->group(function() {
    Route::get('/', function() {
        if (Auth::check()) {
            return redirect()->route('dashboard_');
        } else {
            return redirect(config('backend.uri') . '/auth/login');
        }
    });

    // Auth
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function() {
        Route::get('login', 'AuthController@showLogin')->name('login_');
        Route::post('login', 'AuthController@doLogin');
        Route::get('logout', 'AuthController@doLogout');
    });

    Route::middleware(['AccessPermBackend'])->group(function() {
        // Common
        Route::group(['namespace' => 'Common', 'prefix' => 'common'], function() {
            Route::get('dashboard', 'DashboardController@index')->name('dashboard_');

            Route::group(['prefix' => 'file-manager'], function(){
                Route::get('/', 'FileManagerController@index');
                Route::post('upload', 'FileManagerController@upload');
                Route::post('folder', 'FileManagerController@folder');
                Route::post('delete', 'FileManagerController@delete');
            });
        });

        // Setting
        Route::group(['namespace' => 'Setting', 'prefix' => 'setting'], function() {
            Route::get('setting', 'SettingController@getForm');
            Route::post('setting', 'SettingController@edit');
        });

        // Article category - article
        Route::group(['namespace' => 'Article', 'prefix' => 'article'], function() {
            Route::group(['prefix' => 'category'], function() {
                Route::get('/', 'CategoryController@getList');
                Route::get('page/{pageNumber}', 'CategoryController@getList');

                Route::get('add', 'CategoryController@getForm');
                Route::post('add', 'CategoryController@add');

                Route::get('edit/{id}', 'CategoryController@getForm');
                Route::post('edit/{id}', 'CategoryController@edit');

                Route::post('delete', 'CategoryController@delete');

                Route::post('ajax-status', 'CategoryController@ajaxStatus');
                Route::get('auto-complete', 'CategoryController@autoComplete');
            });

            Route::group(['prefix' => 'article'], function() {
                Route::get('/', 'ArticleController@getList');
                Route::get('page/{pageNumber}', 'ArticleController@getList');

                Route::get('add', 'ArticleController@getForm');
                Route::post('add', 'ArticleController@add');

                Route::get('edit/{id}', 'ArticleController@getForm');
                Route::post('edit/{id}', 'ArticleController@edit');

                Route::post('delete', 'ArticleController@delete');

                Route::post('ajax-status', 'ArticleController@ajaxStatus');
                Route::post('ajax-featured', 'ArticleController@ajaxFeatured');
                Route::get('auto-complete', 'ArticleController@autoComplete');
            });
        });

        // Customer
        Route::group(['namespace' => 'Customer', 'prefix' => 'customer'], function() {
            Route::group(['prefix' => 'customer-group'], function() {
                Route::get('/', 'CustomerGroupController@getList');
                Route::get('page/{pageNumber}', 'CustomerGroupController@getList');

                Route::get('add', 'CustomerGroupController@getForm');
                Route::post('add', 'CustomerGroupController@add');

                Route::get('edit/{id}', 'CustomerGroupController@getForm');
                Route::post('edit/{id}', 'CustomerGroupController@edit');

                Route::post('delete', 'CustomerGroupController@delete');
            });

            Route::group(['prefix' => 'customer'], function() {
                Route::get('/', 'CustomerController@getList');
                Route::get('page/{pageNumber}', 'CustomerController@getList');

                Route::get('add', 'CustomerController@getForm');
                Route::post('add', 'CustomerController@add');

                Route::get('edit/{id}', 'CustomerController@getForm');
                Route::post('edit/{id}', 'CustomerController@edit');

                Route::post('delete', 'CustomerController@delete');

                Route::post('ajax-status', 'CustomerController@ajaxStatus');
                Route::get('auto-complete', 'CustomerController@autoComplete');
            });

            Route::group(['prefix' => 'recharge'], function() {
                Route::get('/', 'RechargeController@getList');
                Route::get('page/{pageNumber}', 'RechargeController@getList');

                Route::get('add', 'RechargeController@getForm');
                Route::post('add', 'RechargeController@add');

                Route::get('edit/{id}', 'RechargeController@getForm');
                Route::post('edit/{id}', 'RechargeController@edit');

                Route::post('delete', 'RechargeController@delete');

                Route::get('auto-complete', 'RechargeController@autoComplete');
            });
        });

    });
});
