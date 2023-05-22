<?php

namespace App\Http\Controllers\Frontend\Contact;

use App\Models\Frontend\Contact\ContactModel;
use App\Http\Controllers\Frontend\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ContactController extends Controller
{
	private $contactModel;

	public function __construct(
        ContactModel $contactModel
    ){
		parent::__construct();
		$this->pathResource = get_path_resource(__DIR__, __CLASS__);
		load_lang($this->pathResource);
		$this->contactModel = $contactModel;
	}

	public function add(){
		return $this->modifiedEntity($this->validateForm(), $this->contactModel);
	}

	public function edit($id){
		return $this->modifiedEntity($this->validateForm($id), $this->contactModel, $id);
	}

	public function delete($id = null){
		return $this->deleteEntity($this->validateDelete($id), $this->contactModel, $id);
	}

	public function getList($page = null){
        $data = [];

		$data['add'] = site_url($this->pathResource . '/add');
		$data['delete'] = site_url($this->pathResource . '/delete');

        $url = '';
        $filterFullName = '';
        if (Request::query('filter_full_name')) {
            $filterFullName = Request::query('filter_full_name');
            $url .= '&filter_full_name=' . urlencode(html_entity_decode(Request::query('filter_full_name'), ENT_QUOTES, 'UTF-8'));
        }

        $filterStatus = '';
        if (Request::has('filter_status')) {
            $filterStatus = Request::query('filter_status');
            $url .= '&filter_status=' . Request::query('filter_status');
        }

        $filterPhone = '';
        if (Request::has('filter_phone')) {
            $filterPhone = Request::query('filter_phone');
            $url .= '&filter_phone=' . urlencode(html_entity_decode(Request::query('filter_phone'), ENT_QUOTES, 'UTF-8'));
        }

        $filterAddress = '';
        if (Request::has('filter_address')) {
            $filterAddress = Request::query('filter_address');
            $url .= '&filter_address=' . urlencode(html_entity_decode(Request::query('filter_address'), ENT_QUOTES, 'UTF-8'));
        }

		$sort = 'full_name';
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
            'filter_full_name'	  => $filterFullName,
            'filter_status'   => $filterStatus,
            'filter_phone'   => $filterPhone,
            'filter_address'   => $filterAddress,
			'sort' => $sort,
			'order' => $order,
			'skip' => ($page - 1) * config('frontend.page_limit'),
			'take' => config('frontend.page_limit'),
		];

		$data['contacts'] = [];
		$total = $this->contactModel->getTotal($filterData);
		$results = $this->contactModel->getList($filterData);

		$orderNumber = 1;
		foreach ($results as $result) {
            $skip = ($page - 1) * config('frontend.page_limit');
			$data['contacts'][] = [
				'id' => $result['id'],
				'full_name' => $result['full_name'],
				'address' => $result['address'],
				'phone' => $result['phone'],
				'note' => $result['note'],
				'sort_order' => $result['sort_order'],
				'status' => $result['status'],
				'order_number' => $skip + $orderNumber,
				'edit' => site_url($this->pathResource . '/edit/' . $result['id']),
				'delete'      => site_url($this->pathResource . '/delete/' . $result['id']),
			];
            $orderNumber++;
		}

        $data['filter_full_name'] = $filterFullName;
        $data['filter_status'] = $filterStatus;
        $data['filter_phone'] = $filterPhone;
        $data['filter_address'] = $filterAddress;

        $sortUrl = Str::of($url)
            ->replace('&sort=full_name', '')
            ->replace('&sort=phone', '')
            ->replace('&sort=sort_order', '')
            ->replace('&order=desc', '')
            ->replace('&order=asc', '');

        if ($order == 'asc') {
            $sortUrl .= '&order=desc';
        } else {
            $sortUrl .= '&order=asc';
        }

		$data['sort_full_name'] = site_url($this->pathResource) . '?' . $sortUrl . '&sort=full_name';
		$data['sort_phone'] = site_url($this->pathResource) . '?' . $sortUrl . '&sort=phone';
		$data['sort_sort_order'] = site_url($this->pathResource) . '?' . $sortUrl . '&sort=sort_order';

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['pagination'] = $this->renderPaging($total, $page, $url);
		return load_view($this->pathResource . '_list', $data);
	}

	public function getForm($id = null){
        $data = [];

		if ($id) {
			$info = $this->contactModel->getById($id);
		}

		$data['is_add'] = false;

		$data['action'] = site_url($this->pathResource . '/edit/' . $id);
		if (!isset($id)) {
            $data['is_add'] = true;
			$data['action'] = site_url($this->pathResource . '/add');
		}
		$data['back'] = site_url($this->pathResource);

		$customers = [
			'full_name' => '',
			'address' => '',
			'phone' => '',
			'note' => '',
			'sort_order' => 0,
			'status' => 1,
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

	protected function validateForm($id = null){
		$rules = [
			'full_name' => 'required',
			'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:8|max:20',
            'address' => 'max:255',
            'note' => 'max:255',
		];

		$messages = [
			'full_name.required' => lang_trans('error_full_name'),
			'phone.required' => lang_trans('error_phone'),
			'phone.regex' => lang_trans('error_phone_regex'),
			'phone.min' => lang_trans('error_phone_regex'),
			'phone.max' => lang_trans('error_phone_regex'),
            'address.max' => lang_trans('error_address'),
            'note.max' => lang_trans('error_note'),
		];
		$validator = Validator::make(Request::all(), $rules, $messages);
		return $validator;
	}

	protected function validateDelete($id = null){
		if (!$id && !Request::has('selected')){
			return false;
		}
		return true;
	}

    public function ajaxStatus(){
        $json = [];
        if(!Request::has('value')) {
            $json['error'] = lang_trans('error_post');
        }
        if(!isset($json['error'])) {
            $value = explode(',', Request::post('value'));

            $id = $value[0];
            $status = $value[1];
            $convert = $status ? 0 : 1;
            $json['status'] = $convert;
            $this->contactModel->updateStatus((int)$id, (int)$convert);
            $json['success'] = true;
            $json['status'] = $convert;
            $json['id'] = $id;
        }
        Return Response::json($json);
    }

    public function autoComplete() {
        $json = [];

        $limit = 15;
        if (Request::query('limit')) {
            $limit = Request::query('limit');
        }

        $filterData = array(
            'filter_full_name' => '',
            'filter_phone' => '',
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

        if (Request::has('filter_phone')) {
            $filterPhone = '';
            if (Request::query('filter_phone')) {
                $filterPhone = Request::query('filter_phone');
            }
            $filterData['filter_phone'] = $filterPhone;
        }

        $results = $this->contactModel->getList($filterData);
        foreach ($results as $result) {
            $json[] = array(
                'id' => $result['id'],
                'full_name'       => strip_tags(html_decode($result['full_name'])),
                'phone'       => $result['phone'],
            );
        }
        return Response::json($json);
    }
}
