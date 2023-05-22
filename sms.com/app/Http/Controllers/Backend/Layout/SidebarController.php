<?php

namespace App\Http\Controllers\Backend\Layout;

use App\Http\Controllers\Backend\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\View\View;

class SidebarController extends Controller
{
    public function compose(View $view){
        $data['full_name'] = Auth::user()->full_name;
        $data['navigation'] = $this->navigation();
        $view->with($data);
    }

    protected function navigation(){
        load_lang(get_path_resource(__DIR__, __CLASS__));
        return $this->menuBar($this->uriSegment(2), $this->uriSegment([2, 3]), $this->uriSegment([2, 3, 4]));
    }

    protected function menuBar($uriSegment2 = null, $uriSegment23 = null, $uriSegment234 = null){
        $menu = [
            'main_navigation' => [
                'header' => true,
                'class' => 'header',
                'name' => lang_trans('text_heading'),
                'route' => false,
            ],
            'dashboard' => [
                'class' => ($uriSegment2 == 'common') ? 'treeview active' : 'treeview',
                'name' => lang_trans('text_dashboard'),
                'icon' => 'fa fa-dashboard',
                'route' => site_url('common/dashboard'),
            ],
            'article' => [
                'class' => ($uriSegment2 == 'article') ? 'treeview active' : 'treeview',
                'name' => lang_trans('text_article'),
                'icon' => 'fa fa-globe',
                'route' => false,
                'children' => [
                    [
                        'class' => ($uriSegment23 == 'article/category') ? 'active' : '',
                        'name' => lang_trans('text_category'),
                        'route' => site_url('article/category'),
                    ],
                    [
                        'class' => ($uriSegment23 == 'article/article') ? 'active' : '',
                        'name' => lang_trans('text_article'),
                        'route' => site_url('article/article'),
                    ],
                ]
            ],
            'customer' => [
                'class' => ($uriSegment2 == 'customer') ? 'treeview active' : 'treeview',
                'name' => lang_trans('text_customer'),
                'icon' => 'fa fa-user',
                'route' => false,
                'children' => [
                    [
                        'class' => ($uriSegment23 == 'customer/customer-group') ? 'active' : '',
                        'name' => lang_trans('text_customer_group'),
                        'route' => site_url('customer/customer-group'),
                    ],
                    [
                        'class' => ($uriSegment23 == 'customer/customer') ? 'active' : '',
                        'name' => lang_trans('text_customer'),
                        'route' => site_url('customer/customer'),
                    ],
                    [
                        'class' => ($uriSegment23 == 'customer/recharge') ? 'active' : '',
                        'name' => lang_trans('text_customer_recharge'),
                        'route' => site_url('customer/recharge'),
                    ],
                ]
            ],
            'setting' => [
                'class' => ($uriSegment2 == 'setting') ? 'treeview active' : 'treeview',
                'name' => lang_trans('text_setting'),
                'icon' => 'fa fa-cog',
                'route' => false,
                'children' => [
                    [
                        'class' => ($uriSegment23 == 'setting/setting') ? 'active' : '',
                        'name' => lang_trans('text_setting_setting'),
                        'route' => site_url('setting/setting'),
                    ],
                ]
            ],
            /*'develop' => [
                'header' => true,
                'class' => 'header',
                'name' => lang_trans('text_develop'),
                'route' => false,
            ],
            'localisation' => [
                'class' => ($uriSegment2 == 'localisation') ? 'treeview active' : 'treeview',
                'name' => lang_trans('text_localisation'),
                'icon' => 'fa fa-podcast',
                'route' => false,
                'children' => [
                    [
                        'class' => ($uriSegment23 == 'localisation/language') ? 'active' : '',
                        'name' => lang_trans('text_localisation_language'),
                        'route' => site_url('localisation/language'),
                    ],
                ]
            ]*/
        ];

        return $menu;
    }

    private function uriSegment($data) {
	    $uriSegment = '';
    	if (is_array($data)) {
    		foreach ($data as $key => $value) {
    			if ($key > 0) {
				    $uriSegment .= '/' . Request::segment($value);
			    } else {
				    $uriSegment = Request::segment($value);
			    }
		    }
	    } else {
		    $uriSegment = Request::segment($data);
	    }
	    return $uriSegment;
    }
}
