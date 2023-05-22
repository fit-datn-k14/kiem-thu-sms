<?php

namespace App\Http\Controllers\Frontend\Contact;

use App\Models\Frontend\Contact\ContactModel;
use App\Models\Frontend\Contact\SmsModel;
use App\Http\Controllers\Frontend\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class SmsHistoryController extends Controller
{
    private $contactModel;
    private $smsModel;

    public function __construct(
        ContactModel $contactModel,
        SmsModel $smsModel
    ){
        parent::__construct();
        $this->pathResource = get_path_resource(__DIR__, __CLASS__);
        load_lang($this->pathResource);
        $this->contactModel = $contactModel;
        $this->smsModel = $smsModel;
    }

    public function delete($id = null){
        return $this->deleteEntity($this->validateDelete($id), $this->smsModel, $id);
    }

    public function getList($page = null){
        $data = [];

        $data['delete'] = site_url($this->pathResource . '/delete');

        $url = '';

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
            'sort' => $sort,
            'order' => $order,
            'skip' => ($page - 1) * config('frontend.page_limit'),
            'take' => config('frontend.page_limit'),
        ];

        $data['smss'] = [];
        $total = $this->smsModel->getTotal($filterData);
        $results = $this->smsModel->getList($filterData);

        $orderNumber = 1;
        foreach ($results as $result) {
            $skip = ($page - 1) * config('frontend.page_limit');
            $data['smss'][] = [
                'id' => $result['id'],
                'total_sms' => $result['total_sms'],
                'total_success' => $result['total_success'],
                'total_fail' => $result['total_fail'],
                'created_at' => date(config('main.datetime_format'), strtotime($result['created_at'])),
                'order_number' => $skip + $orderNumber,
                'info'      => site_url($this->pathResource . '/info/' . $result['id']),
                'delete'      => site_url($this->pathResource . '/delete/' . $result['id']),
            ];
            $orderNumber++;
        }

        $sortUrl = Str::of($url)
            ->replace('&sort=created_at', '')
            ->replace('&order=desc', '')
            ->replace('&order=asc', '');

        if ($order == 'asc') {
            $sortUrl .= '&order=desc';
        } else {
            $sortUrl .= '&order=asc';
        }

        $data['sort_created_at'] = site_url($this->pathResource) . '?' . $sortUrl . '&sort=created_at';

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['pagination'] = $this->renderPaging($total, $page, $url, config('frontend.page_limit'));

        return load_view($this->pathResource . '_list', $data);
    }

    public function getInfo($id, $page = null){
        $data = [];

        if (!(int)$id) {
            return redirect(site_url($this->pathResource));
        }

        $data['back'] = site_url($this->pathResource);

        if (!isset($page)) {
            $page = 1;
        }
        $page = (int)$page;

        $filterData = [
            'skip' => ($page - 1) * config('frontend.page_sms_limit'),
            'take' => config('frontend.page_sms_limit'),
        ];

        $data['sms_logs'] = [];
        $total = $this->smsModel->getTotalSmsLog($id, $filterData);
        $results = $this->smsModel->getListSmsLog($id, $filterData);

        $orderNumber = 1;
        foreach ($results as $result) {
            $skip = ($page - 1) * config('frontend.page_sms_limit');
            $data['sms_logs'][] = [
                'id' => $result['id'],
                'total_sms' => $result['total_sms'],
                'is_success' => $result['is_success'],
                'phone' => $result['phone'],
                'msg' => $result['msg'],
                'content' => $result['content'],
                'full_name' => $result['full_name'],
                'address' => $result['address'],
                'order_number' => $skip + $orderNumber,
            ];
            $orderNumber++;
        }

        $paginate = [
            'total' => $total,
            'page' => $page,
            'limit' => config('frontend.page_sms_limit'),
            'url' => site_url($this->pathResource  . '/info/' . $id . '/page/{page}'),
        ];
        $data['pagination'] = $this->renderPaging(null, null, null, null, $paginate);

        return load_view($this->pathResource . '_info', $data);
    }

    protected function validateDelete($id = null){
        if (!$id && !Request::has('selected')){
            return false;
        }
        return true;
    }
}
