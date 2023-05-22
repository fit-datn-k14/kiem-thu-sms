<?php

namespace App\Http\Controllers\Frontend\Layout;

use App\Http\Controllers\Frontend\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\View\View;

class SidebarController extends Controller
{
    public function compose(View $view){
        $data['full_name'] = Auth::guard('customer')->user()->full_name;
        $data['email'] = Auth::guard('customer')->user()->email;
        $data['navigation'] = $this->navigation();
        $view->with($data);
    }

    protected function navigation(){
        load_lang(get_path_resource(__DIR__, __CLASS__));
        return $this->menuBar($this->uriSegment(1), $this->uriSegment([1, 2]), $this->uriSegment([1, 2, 3]));
    }

    protected function menuBar($uriSegment2 = null, $uriSegment23 = null, $uriSegment234 = null){
        $menu = [
            'dashboard' => [
                'class' => ($uriSegment23 == 'common/dashboard') ? 'mdc-drawer-link-second active' : 'mdc-drawer-link-second',
                'name' => lang_trans('text_dashboard'),
                'icon' => 'home',
                'route' => site_url('common/dashboard'),
            ],
            'contact' => [
                'class' => ($uriSegment23 == 'contact/contact') ? 'mdc-drawer-link-second active' : 'mdc-drawer-link-second',
                'name' => lang_trans('text_contact'),
                'icon' => 'description',
                'route' => site_url('contact/contact'),
            ],
            'sms_group' => [
                'class' => ($uriSegment23 == 'contact/sms-group') ? 'mdc-drawer-link-second active' : 'mdc-drawer-link-second',
                'name' => lang_trans('text_contact_sms_group'),
                'icon' => 'pages',
                'route' => site_url('contact/sms-group'),
            ],
            'sms_single' => [
                'class' => ($uriSegment23 == 'contact/sms-single') ? 'mdc-drawer-link-second active' : 'mdc-drawer-link-second',
                'name' => lang_trans('text_contact_sms_single'),
                'icon' => 'pages',
                'route' => site_url('contact/sms-single'),
            ],
            /*'sms_history' => [
                'class' => ($uriSegment23 == 'contact/sms-history') ? 'mdc-drawer-link-second active' : 'mdc-drawer-link-second',
                'name' => lang_trans('text_contact_sms_history'),
                'icon' => 'track_changes',
                'route' => site_url('contact/sms-history'),
            ],
            'customer_recharge_history' => [
                'class' => ($uriSegment23 == 'customer/recharge-history') ? 'mdc-drawer-link-second active' : 'mdc-drawer-link-second',
                'name' => lang_trans('text_customer_recharge_history'),
                'icon' => 'payment',
                'route' => site_url('customer/recharge-history'),
            ],*/
            'customer_history' => [
                'class_expanded' => ($uriSegment23 == 'contact/sms-history' || $uriSegment23 == 'customer/recharge-history') ? 'expanded expanded-block' : '',
                'name' => lang_trans('text_history'),
                'icon' => 'pie_chart_outlined',
                'route' => false,
                'children' => [
                    [
                        'class' => ($uriSegment23 == 'contact/sms-history') ? 'mdc-drawer-link-second active' : 'mdc-drawer-link-second',
                        'name' => lang_trans('text_contact_sms_history'),
                        'route' => site_url('contact/sms-history'),
                    ],
                    [
                        'class' => ($uriSegment23 == 'customer/recharge-history') ? 'mdc-drawer-link-second active' : 'mdc-drawer-link-second',
                        'name' => lang_trans('text_customer_recharge_history'),
                        'route' => site_url('customer/recharge-history'),
                    ],
                ]
            ],
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
