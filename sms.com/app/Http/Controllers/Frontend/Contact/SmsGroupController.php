<?php

namespace App\Http\Controllers\Frontend\Contact;

use App\Models\Frontend\Contact\ContactModel;
use App\Models\Frontend\Contact\SmsModel;
use App\Http\Controllers\Frontend\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SmsGroupController extends Controller
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
            'filter_status'   => 1,
            'filter_phone'   => $filterPhone,
            'filter_address'   => $filterAddress,
            'sort' => $sort,
            'order' => $order,
            'skip' => ($page - 1) * config('frontend.page_sms_limit'),
            'take' => config('frontend.page_sms_limit'),
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
                'order_number' => $skip + $orderNumber,
            ];
            $orderNumber++;
        }

        $data['filter_full_name'] = $filterFullName;
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

        $data['pagination'] = $this->renderPaging($total, $page, $url, config('frontend.page_sms_limit'));

        // Sms check
        $data['sms_config'] = $this->getSmsConfig();
        // End check

        return load_view($this->pathResource . '_list', $data);
    }

    public function sendSms() {
        $json = [];
        if(!Request::has('content') || !Request::has('contact')) {
            $json['error'] = 'Bạn chưa chọn danh sách liên lạc gửi tin hoặc nội dung tin nhắn trống';
        } else {
            if(!Request::post('content')){
                $json['error'] = 'Nội dung tin nhắn không được để trống';
            }
            if(!Request::post('contact')){
                $json['error'] = 'Bạn chưa chọn danh sách liên lạc gửi tin';
            }
        }
        if(!isset($json['error'])) {
            $smsConfig = $this->getSmsConfig();

            if ($smsConfig['total_sms_remain']) {
                $smsPostContent = Request::post('content');
                $smsPostContact = Request::post('contact');

                $contentReadySend = '';
                if ($smsConfig['sms_prefix_length']) {
                    $contentReadySend = $smsConfig['customer_sms_prefix'] . ' ';
                }
                $contentReadySend .= convert_vi_to_en(html_decode($smsPostContent));
                $smsLength = Str::length($contentReadySend);

                $smsCharactersNo = config('sms.sms_characters_no');
                $smsNo1 = $smsCharactersNo['smsNo1'];
                $smsNo2 = $smsCharactersNo['smsNo2'];
                $smsNo3 = $smsCharactersNo['smsNo3'];

                if ($smsLength <= $smsNo1) {
                    $totalSmsLog = 1;
                } elseif (($smsLength > $smsNo1) && ($smsLength <= $smsNo2 )) {
                    $totalSmsLog = 2;
                } elseif (($smsLength > $smsNo2) && ($smsLength <= $smsNo3 )) {
                    $totalSmsLog = 3;
                } else {
                    $totalSmsLog = 4;
                }

                $totalSmsSent = 0;
                $totalSmsSuccess = 0;
                $totalSmsFail = 0;

                $totalSendContactSuccess = 0;
                $totalSendContactFail = 0;
                $contacts = [];

                $smsApiConfig = $this->getSmsApiConfig();

                $contactIds = explode(',', $smsPostContact);
                $contactIds = array_filter($contactIds);

                if ($totalSmsLog > 3) {
                    $json['error'] =  'Số lượng ký tự tin nhắn đã vượt quá số ký tự cho phép (Tối đa ' . $smsNo3 . ' ký tự).';
                } elseif ($smsConfig['total_sms_remain'] >= (count($contactIds) * $totalSmsLog)) {
                    $canSendSms = true;
                    foreach ($contactIds as $contactId) {
                        if (($totalSmsSuccess + $totalSmsLog) > $smsConfig['total_sms_remain']) {
                            $canSendSms = false;
                        }

                        $phone = $this->smsModel->getContactPhone($contactId);
                        if (!$phone) {
                            continue;
                        }

                        if ($canSendSms) {
                            $obj = app('SmsHandle')->sendSms($smsApiConfig, $phone, urldecode($contentReadySend));

                            $totalSmsSent += $totalSmsLog;
                            if ($obj['code_result'] == 100) {
                                $totalSendContactSuccess += 1;
                                $totalSmsSuccess += $totalSmsLog;
                                $contacts[] = array(
                                    'contact_id' => $contactId,
                                    'total_sms' => $totalSmsLog,
                                    'phone' => $phone,
                                    'api_sms_id' => $obj['api_sms_id'],
                                    'is_success' => 1,
                                    'content' => $contentReadySend,
                                    'msg' => 'Gửi thành công',
                                );
                            } else {
                                $totalSendContactFail += 1;
                                $totalSmsFail += $totalSmsLog;
                                $contacts[] = array(
                                    'contact_id' => $contactId,
                                    'total_sms' => $totalSmsLog,
                                    'phone' => $phone,
                                    'api_sms_id' => '',
                                    'is_success' => 0,
                                    'content' => $contentReadySend,
                                    'msg' => $obj['error_message'] ? $obj['error_message'] : 'Có lỗi xảy ra trong quá trình xử lý'
                                );
                            }
                        } else {
                            $totalSendContactFail += 1;
                            $totalSmsFail += $totalSmsLog;
                            $contacts[] = array(
                                'contact_id' => $contactId,
                                'total_sms' => $totalSmsLog,
                                'phone' => $phone,
                                'api_sms_id' => '',
                                'is_success' => 0,
                                'content' => $contentReadySend,
                                'msg' => 'Không đủ số tiền trong tài khoản để gửi tin',
                            );
                        }
                    }

                    $smsDataSent = [
                        'total_sms' => $totalSmsSent,
                        'total_success' => $totalSmsSuccess,
                        'total_fail' => $totalSmsFail,
                        'contacts' => $contacts,
                        'sms_price' => $smsConfig['sms_price'],
                    ];

                    $this->smsModel->addSms($smsDataSent);

                    $json['data'] =  "Đã gửi tin nhắn thành công tới: {$totalSendContactSuccess} SĐT.<br>Lỗi gửi tin: {$totalSendContactFail} SĐT.<br>Tổng số tin nhắn đã gửi thành công: {$totalSmsSuccess}/{$totalSmsSent}.";
                } else {
                    $json['error'] =  'Không đủ số tiền để gửi đi tất cả các tin';
                }
            } else {
                $json['error'] =  'Không đủ số tiền để gửi tin';
            }
        }
        Return Response::json($json);
    }
}
