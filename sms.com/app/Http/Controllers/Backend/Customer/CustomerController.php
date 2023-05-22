<?php

namespace App\Http\Controllers\Backend\Customer;

use App\Models\Backend\Customer\CustomerGroupModel;
use App\Models\Backend\Customer\CustomerModel;
use App\Http\Controllers\Backend\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;

class CustomerController extends Controller
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
        return $this->modifiedEntity($this->validateForm(), $this->customerModel);
    }

    public function edit($id){
        return $this->modifiedEntity($this->validateForm($id), $this->customerModel, $id);
    }

    public function delete(){
        return $this->deleteEntity($this->validateDelete(), $this->customerModel);
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

        $filterStatus = '';
        if (Request::has('filter_status')) {
            $filterStatus = Request::query('filter_status');
            $url .= '&filter_status=' . Request::query('filter_status');
        }

        $filterEmail = '';
        if (Request::has('filter_email')) {
            $filterEmail = Request::query('filter_email');
            $url .= '&filter_email=' . urlencode(html_entity_decode(Request::query('filter_email'), ENT_QUOTES, 'UTF-8'));
        }

        $filterCustomerGroupId = null;
        if(Request::has('filter_customer_group_id')) {
            $filterCustomerGroupId = Request::query('filter_customer_group_id');
            $url .= '&filter_customer_group_id=' . urlencode(html_entity_decode(Request::query('filter_customer_group_id'), ENT_QUOTES, 'UTF-8'));
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
            'filter_status'   => $filterStatus,
            'filter_email'   => $filterEmail,
            'filter_customer_group_id' => $filterCustomerGroupId,
            'sort' => $sort,
            'order' => $order,
            'skip' => ($page - 1) * (int)config_get('config_limit_admin'),
            'take' => (int)config_get('config_limit_admin'),
        ];

        $data['customers'] = [];
        $total = $this->customerModel->getTotal();
        $results = $this->customerModel->getList($filterData);
        foreach ($results as $result) {
            $data['customers'][] = [
                'id' => $result['id'],
                'full_name' => $result['full_name'],
                'customer_group' => $result['customer_group'],
                'email' => $result['email'],
                'sms_brand_name' => $result['sms_brand_name'],
                'sms_prefix' => $result['sms_prefix'],
                'ip' => $result['ip'],
                'status' => $result['status'],
                'money' => format_currency($result['money']),
                'total_sms' => format_currency($result['total_sms']),
                'sms_price' => format_currency($result['sms_price']),
                'created_at' => date(config('main.datetime_format'), strtotime($result['created_at'])),
                'edit' => site_url($this->pathResource . '/edit/' . $result['id']),
                'delete'      => site_url($this->pathResource . '/delete/' . $result['id']),
            ];
        }

        $data['customer_groups'] = [];
        $results = $this->customerGroupModel->getList($filterData);
        foreach ($results as $result) {
            $data['customer_groups'][] = [
                'id' => $result['id'],
                'name' => $result['name'],
            ];
        }

        $data['filter_full_name'] = $filterFullName;
        $data['filter_status'] = $filterStatus;
        $data['filter_email'] = $filterEmail;
        $data['filter_customer_group_id'] = $filterCustomerGroupId;

        $sortUrl = Str::of($url)
            ->replace('&sort=full_name', '')
            ->replace('&sort=email', '')
            ->replace('&sort=created_at', '')
            ->replace('&sort=status', '')
            ->replace('&sort=total_sms', '')
            ->replace('&sort=sms_price', '')
            ->replace('&sort=money', '')
            ->replace('&order=desc', '')
            ->replace('&order=asc', '');

        if ($order == 'asc') {
            $sortUrl .= '&order=desc';
        } else {
            $sortUrl .= '&order=asc';
        }

        $data['sort_full_name'] = site_url($this->pathResource) . '?' . $sortUrl . '&sort=full_name';
        $data['sort_email'] = site_url($this->pathResource) . '?' . $sortUrl . '&sort=email';
        $data['sort_created_at'] = site_url($this->pathResource) . '?' . $sortUrl . '&sort=created_at';
        $data['sort_status'] = site_url($this->pathResource) . '?' . $sortUrl . '&sort=status';
        $data['sort_total_sms'] = site_url($this->pathResource) . '?' . $sortUrl . '&sort=total_sms';
        $data['sort_sms_price'] = site_url($this->pathResource) . '?' . $sortUrl . '&sort=sms_price';
        $data['sort_money'] = site_url($this->pathResource) . '?' . $sortUrl . '&sort=money';

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['pagination'] = $this->renderPaging($total, $page, $url);
        return load_view($this->pathResource . '_list', $data);
    }

    public function getForm($id = null){
        $data = $this->breadcrumbs();

        $data['is_add'] = true;
        if ($id) {
            $info = $this->customerModel->getById($id);
            if (!empty($info)) {
                $data['is_add'] = false;
            }
        }

        $data['action'] = site_url($this->pathResource . '/edit/' . $id);
        if (!isset($id)) {
            $data['action'] = site_url($this->pathResource . '/add');
        }
        $data['back'] = site_url($this->pathResource);

        $customers = [
            'full_name' => '',
            'customer_group_id' => '',
            'email' => '',
            'image' => '',
            'sms_brand_name' => '',
            'sms_prefix' => '',
            'status' => 1,
            'newsletter' => 0,
            'sms_price' => 0,
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

        if (Request::old('image') && File::isFile(IMAGE_PATH . Request::old('image'))) {
            $data['thumb'] = image_resize_full(Request::old('image'), true);
        } elseif (!empty($info) && File::isFile(IMAGE_PATH . $info['image'])) {
            $data['thumb'] = image_resize_full($info['image'], true);
        } else {
            $data['thumb'] = no_image();
        }
        $data['no_image'] = no_image();

        $data['customer_groups'] = $this->customerGroupModel->getList();

        return load_view($this->pathResource . '_form', $data);
    }

    protected function validateForm($id = 0){
        $rules = [
            'full_name' => 'required|min:1|max:255',
            'customer_group_id' => 'required',
            'password' => 'validate_password:' . $id,
            'password_re_enter' => 'same:password',
            'sms_brand_name' => 'max:40',
            'sms_prefix' => 'max:96',
        ];

        if (!$id) {
            $rules['email'] = [
                'required',
                'email',
                'unique:customer,email',
            ];
            $rules['password'] = 'required|min:6|max:64';
        }

        $messages = [
            'full_name.required' => lang_trans('error_full_name'),
            'full_name.min' => lang_trans('error_full_name'),
            'full_name.max' => lang_trans('error_full_name'),
            'customer_group_id.required' => lang_trans('error_customer_group'),
            'email.required' => lang_trans('error_email_required'),
            'email.email' => lang_trans('error_email'),
            'email.unique' => lang_trans('error_email_exists'),
            'password.min' => lang_trans('error_password'),
            'password.max' => lang_trans('error_password'),
            'password.required' => lang_trans('error_password'),
            'password.validate_password' => lang_trans('error_password'),
            'password_re_enter.same' => lang_trans('error_password_same'),
            'sms_brand_name.max' => lang_trans('error_sms_brand_name'),
            'sms_prefix.max' => lang_trans('error_sms_prefix'),
        ];
        $validator = Validator::make(Request::all(), $rules, $messages);
        $validator->addExtension('validate_password', function($attribute, $value, $parameters, $validator){
            if ($parameters['0'] && !empty(Request::input('password')) &&
                ((Str::length(Request::input('password')) < 8) || (Str::length(Request::input('password')) > 64))) {
                return false;
            }
            return true;
        });
        return $validator;
    }

    protected function validateDelete(){
        if (!Request::has('selected')){
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
            $this->customerModel->updateStatus((int)$id, (int)$convert);
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

        $results = $this->customerModel->getList($filterData);
        foreach ($results as $result) {
            $json[] = array(
                'id' => $result['id'],
                'full_name'       => strip_tags(html_decode($result['full_name'])),
                'email'       => $result['email'],
                'money'       => $result['money'],
            );
        }
        return Response::json($json);
    }
}
