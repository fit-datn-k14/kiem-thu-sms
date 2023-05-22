<?php

namespace App\Http\Controllers\Backend\Customer;

use App\Models\Backend\Customer\CustomerGroupModel;
use App\Models\Backend\Customer\CustomerModel;
use App\Http\Controllers\Backend\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CustomerGroupController extends Controller
{
	private $customerGroupModel;
	private $customerModel;

	public function __construct(
        CustomerGroupModel $customerGroupModel,
        CustomerModel $customerModel
    ){
		parent::__construct();
		$this->pathResource = get_path_resource(__DIR__, __CLASS__);
		load_lang($this->pathResource);
		$this->customerGroupModel = $customerGroupModel;
		$this->customerModel = $customerModel;
	}

	public function add(){
		return $this->modifiedEntity($this->validateForm(), $this->customerGroupModel);
	}

	public function edit($id){
		return $this->modifiedEntity($this->validateForm($id), $this->customerGroupModel, $id);
	}

	public function delete(){
		return $this->deleteEntity($this->validateDelete(), $this->customerGroupModel);
	}

	public function getList($page = null){
        $data = $this->breadcrumbs();

		$data['add'] = site_url($this->pathResource . '/add');
		$data['delete'] = site_url($this->pathResource . '/delete');

		$url = '';
		$sort = 'name';
		if (Request::has('sort')) {
			$sort = Request::query('sort');
			$url .= '&sort=' . urlencode(html_entity_decode(Request::query('sort'), ENT_QUOTES, 'UTF-8'));
		}

		$order = 'asc';
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
			'sort' => $sort,
			'order' => $order,
			'skip' => ($page - 1) * (int)config_get('config_limit_admin'),
			'take' => (int)config_get('config_limit_admin'),
		];

		$data['customer_groups'] = [];
		$total = $this->customerGroupModel->getTotal();
		$results = $this->customerGroupModel->getList($filterData);
		foreach ($results as $result) {
			$data['customer_groups'][] = [
				'id' => $result['id'],
				'name' => $result['name'],
				'sort_order' => $result['sort_order'],
				'edit' => site_url($this->pathResource . '/edit/' . $result['id']),
				'delete'      => site_url($this->pathResource . '/delete/' . $result['id']),
			];
		}


		$data['sort_name'] = site_url($this->pathResource) . '?' . $url . '&sort=name';
		$data['sort_sort_order'] = site_url($this->pathResource) . '?' . $url . '&sort=sort_order';

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['pagination'] = $this->renderPaging($total, $page, $url);
		return load_view($this->pathResource . '_list', $data);
	}

	public function getForm($id = null){
        $data = $this->breadcrumbs();

		if ($id) {
			$info = $this->customerGroupModel->getById($id);
		}

		$data['action'] = site_url($this->pathResource . '/edit/' . $id);
		if (!isset($id)) {
			$data['action'] = site_url($this->pathResource . '/add');
		}
		$data['back'] = site_url($this->pathResource);

		$customers = [
			'name' => '',
			'sort_order' => 0,
		];
		foreach ($customers as $name => $customer) {
			if (Request::old($name)) {
				$data[$name] = Request::old($name);
			} elseif (!empty($info)) {
				$data[$name] = $info[$name];
			} else {
				$data[$name] = $customer;
			}
		}
		return load_view($this->pathResource . '_form', $data);
	}

	protected function validateForm($id = 0){
		$rules = [
			'name' => 'required|min:1|max:128',
		];

		$messages = [
			'name.required' => lang_trans('error_name'),
			'name.min' => lang_trans('error_name'),
			'name.max' => lang_trans('error_name'),
		];
		$validator = Validator::make(Request::all(), $rules, $messages);
		return $validator;
	}

	protected function validateDelete(){
		if (!Request::has('selected')){
			return false;
		}
        foreach (Request::post('selected') as $groupId) {
            $total = $this->customerModel->getTotalByGroupId($groupId);
            if ($total) {
                $this->errorMessage['flash_warning'] = sprintf(lang_trans('error_customer'), $total);
                return false;
            }
        }
		return true;
	}
}
