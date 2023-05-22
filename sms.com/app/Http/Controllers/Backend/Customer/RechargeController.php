<?php

namespace App\Http\Controllers\Backend\Customer;

use App\Models\Backend\Customer\RechargeModel;
use App\Models\Backend\Customer\CustomerModel;
use App\Http\Controllers\Backend\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class RechargeController extends Controller
{
	private $rechargeModel;
    private $customerModel;

	public function __construct(
        RechargeModel $rechargeModel,
        CustomerModel $customerModel
    ){
		parent::__construct();
		$this->pathResource = get_path_resource(__DIR__, __CLASS__);
		load_lang($this->pathResource);
		$this->rechargeModel = $rechargeModel;
        $this->customerModel = $customerModel;
	}

	public function add(){
		return $this->modifiedEntity($this->validateForm(), $this->rechargeModel);
	}

	public function delete(){
		return $this->deleteEntity($this->validateDelete(), $this->rechargeModel);
	}

	public function getList($page = null){
        $data = $this->breadcrumbs();

		$data['add'] = site_url($this->pathResource . '/add');
		$data['delete'] = site_url($this->pathResource . '/delete');

        $url = '';
        $filterFullName = '';
        if (Request::query('filter_full_name')) {
            $filterFullName = Request::query('filter_full_name');
            $url .= '&filter_full_name=' . urlencode(html_entity_decode(Request::query('filter_full_name'), ENT_QUOTES, 'UTF-8'));
        }

        $filterEmail = '';
        if (Request::has('filter_email')) {
            $filterEmail = Request::query('filter_email');
            $url .= '&filter_email=' . urlencode(html_entity_decode(Request::query('filter_email'), ENT_QUOTES, 'UTF-8'));
        }

		$sort = 'created_at';
		if (Request::has('sort')) {
			$sort = Request::query('sort');
			$url .= '&sort=' . urlencode(html_entity_decode(Request::query('sort'), ENT_QUOTES, 'UTF-8'));
		}

		$order = 'desc';
		if (Request::has('order')) {
			$order = Request::query('order');
			$url .= '&order=' . urlencode(html_entity_decode(Request::query('order'), ENT_QUOTES, 'UTF-8'));
		}

		if (!isset($page)) {
			$page = 1;
		}
		$page = (int)$page;

		$data['selected'] = [];
		if (Request::old('selected')) {
			$data['selected'] = (array)Request::old('selected');
		}

		$filterData = [
            'filter_full_name'	  => $filterFullName,
            'filter_email'   => $filterEmail,
			'sort' => $sort,
			'order' => $order,
			'skip' => ($page - 1) * (int)config_get('config_limit_admin'),
			'take' => (int)config_get('config_limit_admin'),
		];

		$data['recharges'] = [];
		$total = $this->rechargeModel->getTotal();
		$results = $this->rechargeModel->getList($filterData);
		foreach ($results as $key => $result) {
            $data['recharges'][$key] = $result;
            $data['recharges'][$key]['created_at'] = date(config('main.datetime_format'), strtotime($result['created_at']));
			$data['recharges'][$key]['delete'] = site_url($this->pathResource . '/delete/' . $result['id']);
		}

        $data['filter_full_name'] = $filterFullName;
        $data['filter_email'] = $filterEmail;

        $data['sort_full_name'] = site_url($this->pathResource) . '?' . $url . '&sort=full_name';
        $data['sort_email'] = site_url($this->pathResource) . '?' . $url . '&sort=sort_email';
        $data['sort_created_at'] = site_url($this->pathResource) . '?' . $url . '&sort=sort_created_at';

        $data['sort'] = $sort;
        $data['order'] = $order;

		$data['pagination'] = $this->renderPaging($total, $page, $url);
		return load_view($this->pathResource . '_list', $data);
	}

	public function getForm(){
        $data = $this->breadcrumbs();

        $data['action'] = site_url($this->pathResource . '/add');
		$data['back'] = site_url($this->pathResource);

		$customers = [
            'customer_id' => '',
			'amount_paid' => 0,
		];
		foreach ($customers as $name => $customer) {
			if (Request::old($name)) {
				$data[$name] = Request::old($name);
			} else {
				$data[$name] = $customer;
			}
		}

        $data['customer_info'] = [
            'full_name' => '',
            'email' => '',
            'money' => '',
        ];
		if ($data['customer_id']) {
            $info = $this->customerModel->getById($data['customer_id']);
            if (!empty($info)) {
                $data['customer_info'] = [
                    'full_name' => $info['full_name'],
                    'email' => $info['email'],
                    'money' => $info['money'],
                ];
            }
        }

        $moneyRechargeConfig = config_get('config_money_recharge') ?? [];
        $data['config_money_recharge'] = array_filter($moneyRechargeConfig);

        $data['customers'] = $this->customerModel->getList();

		return load_view($this->pathResource . '_form', $data);
	}

	protected function validateForm($id = 0){
		$rules = [
			'customer_id' => 'required|customer_validate',
            'amount_paid' => 'min:1000|integer'
		];

		$messages = [
			'customer_id.required' => lang_trans('error_customer'),
			'customer_id.customer_validate' => lang_trans('error_customer_validate'),
			'amount_paid.min' => lang_trans('error_amount_paid'),
			'amount_paid.integer' => lang_trans('error_amount_paid_integer'),
		];
		$validator = Validator::make(Request::all(), $rules, $messages);
        $validator->addExtension('customer_validate', function($attribute, $value, $parameters, $validator){
            if (!empty(Request::input('customer_id'))) {
                $info = $this->customerModel->getById(Request::input('customer_id'));
                if (!$info) {
                    return false;
                }
            } else {
                return false;
            }
            return true;
        });
		return $validator;
	}

	protected function validateDelete(){
		if (!Request::has('selected')) {
			return false;
		}
		return true;
	}

    public function autoComplete() {
        $json = [];

        $limit = 15;
        if (Request::query('limit')) {
            $limit = Request::query('limit');
        }

        $filterData = array(
            'filter_full_name' => '',
            'skip'        => 0,
            'take'        => $limit
        );

        if (Request::has('filter_full_name')) {
            $filterFullName = '';
            if (Request::query('filter_full_name')) {
                $filterFullName = Request::query('filter_full_name');
            }
            $filterData['filter_full_name'] = $filterFullName;
        }

        if (Request::has('filter_email')) {
            $filterEmail = '';
            if (Request::query('filter_email')) {
                $filterEmail = Request::query('filter_email');
            }
            $filterData['filter_email'] = $filterEmail;
        }

        $results = $this->rechargeModel->getList($filterData);
        foreach ($results as $result) {
            $json[] = array(
                'id' => $result['id'],
                'full_name'       => strip_tags(html_decode($result['full_name'])),
                'email'       => $result['email'],
            );
        }
        return Response::json($json);
    }
}
