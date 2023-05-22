<?php

namespace App\Http\Controllers\Frontend\Common;

use App\Http\Controllers\Frontend\Controller;
use App\Models\Frontend\Common\CommonModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    private $commonModel;

    public function __construct(
        CommonModel $commonModel
    ) {
        parent::__construct();
        $this->pathResource = get_path_resource(__DIR__, __CLASS__);
        load_lang($this->pathResource);
        $this->commonModel = $commonModel;
    }

    public function index() {
        $data = [];
        $data['customer_info'] = $this->getSmsConfig();
        $data['total_contact'] = $this->commonModel->getTotalContact();
        $data['total_contact_active'] = $this->commonModel->getTotalContactActive();
        $data['statistics'] = $this->statistics();

        return load_view($this->pathResource, $data);
    }

    private function statistics() {
        $dates = [
            date('d/m/Y'),
            date('d/m/Y', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'))),
            date('d/m/Y', mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'))),
            date('d/m/Y', mktime(0, 0, 0, date('m'), date('d') - 3, date('Y'))),
            date('d/m/Y', mktime(0, 0, 0, date('m'), date('d') - 4, date('Y'))),
            date('d/m/Y', mktime(0, 0, 0, date('m'), date('d') - 5, date('Y'))),
            date('d/m/Y', mktime(0, 0, 0, date('m'), date('d') - 6, date('Y'))),
            date('d/m/Y', mktime(0, 0, 0, date('m'), date('d') - 7, date('Y'))),
            date('d/m/Y', mktime(0, 0, 0, date('m'), date('d') - 8, date('Y'))),
            date('d/m/Y', mktime(0, 0, 0, date('m'), date('d') - 9, date('Y'))),
        ];
        $dateDisplay = array_reverse($dates);

        $filterData = [
            'filter_from' => date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - (count($dates) - 1), date('Y'))),
            'filter_to' => date('Y-m-d'),
        ];

        $totalSuccess = 0;
        $smsSent = [];
        $results = $this->commonModel->statisticSmsSent($filterData);

        foreach ($dateDisplay as $key => $date) {
            $smsSent[$key] = [
                'label' => $date,
                'success' => 0,
                'fail' => 0,
            ];

            foreach ($results as $result) {
                $dataResult = date('d/m/Y', strtotime($result['created_at']));
                if ($date == $dataResult) {
                    $totalSuccess += $result['total_success'];
                    $smsSent[$key]['success'] += $result['total_success'];
                    $smsSent[$key]['fail'] += $result['total_fail'];
                }
            }
        }

        $statistics = [
            'total_success' => $totalSuccess,
            'data_sms_sent' => $smsSent,
        ];

        return $statistics;
    }
}
