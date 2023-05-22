@extends('frontend.layout.master')
@section('heading_title', lang_trans('heading_title'))
@section('content')
    <div class="mdc-layout-grid__inner">
        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop mdc-layout-grid__cell--span-4-tablet">
            <div class="mdc-card info-card info-card--danger">
                <div class="card-inner">
                    <h5 class="card-title">Số dư hiện tại</h5>
                    <h5 class="font-weight-light pb-2 mb-1 border-bottom">{{ format_currency($customer_info['customer_money'], true) }}</h5>
                    <p class="tx-12 text-muted">Số dư hiện tại</p>
                    <div class="card-icon-wrapper">
                        <i class="material-icons">attach_money</i>
                    </div>
                </div>
            </div>
        </div>
        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop mdc-layout-grid__cell--span-4-tablet">
            <div class="mdc-card info-card info-card--primary">
                <div class="card-inner">
                    <h5 class="card-title">Số SMS hiện có</h5>
                    <h5 class="font-weight-light pb-2 mb-1 border-bottom">{{ format_currency($customer_info['total_sms_remain']) }}</h5>
                    <p class="tx-12 text-muted">{{ format_currency($customer_info['sms_price'], true) }} / SMS</p>
                    <div class="card-icon-wrapper">
                        <i class="material-icons">contact_mail</i>
                    </div>
                </div>
            </div>
        </div>
        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop mdc-layout-grid__cell--span-4-tablet">
            <div class="mdc-card info-card info-card--success">
                <div class="card-inner">
                    <h5 class="card-title">Danh sách liên lạc</h5>
                    <h5 class="font-weight-light pb-2 mb-1 border-bottom"><strong>{{ format_currency($total_contact_active) }}</strong> kích hoạt</h5>
                    <p class="tx-12 text-muted">Tổng số <strong>{{ format_currency($total_contact) }}</strong> liên lạc</p>
                    <div class="card-icon-wrapper">
                        <i class="material-icons">dvr</i>
                    </div>
                </div>
            </div>
        </div>
        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop mdc-layout-grid__cell--span-4-tablet">
            <div class="mdc-card info-card info-card--info">
                <div class="card-inner">
                    <h5 class="card-title">Số SMS đã gửi</h5>
                    <h5 class="font-weight-light pb-2 mb-1 border-bottom">{{ format_currency($customer_info['customer_total_sms_sent']) }}</h5>
                    <p class="tx-12 text-muted">SMS gửi thành công</p>
                    <div class="card-icon-wrapper">
                        <i class="material-icons">trending_up</i>
                    </div>
                </div>
            </div>
        </div>
        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12 mdc-layout-grid__cell--span-12-tablet">
            <div class="mdc-card">
                <div class="d-flex d-lg-block d-xl-flex justify-content-between">
                    <div>
                        <h4 class="card-title statistic-chart-title">Thống kê số SMS đã gửi 10 ngày gần đây</h4>
                        <h6 class="card-sub-title">Số SMS gửi thành công: <strong>{{ $statistics['total_success'] }}</strong></h6>
                    </div>
                    <div id="sales-legend" class="d-flex flex-wrap"></div>
                </div>
                <div class="chart-container mt-4">
                    <canvas id="chart-sales" height="60"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script_footer')
    <script>
        $(function() {
            if ($("#chart-sales").length) {
                var salesChartCanvas = $("#chart-sales").get(0).getContext("2d");
                var gradient1 = salesChartCanvas.createLinearGradient(0, 0, 0, 230);
                gradient1.addColorStop(0, '#1bbd88');
                gradient1.addColorStop(1, 'rgba(255, 255, 255, 0)');

                var gradient2 = salesChartCanvas.createLinearGradient(0, 0, 0, 160);
                gradient2.addColorStop(0, '#ff420f');
                gradient2.addColorStop(1, 'rgba(255, 255, 255, 0)');

                var labels = [];
                var datasetSuccess = [];
                var datasetFail = [];
                @foreach($statistics['data_sms_sent'] as $data_sms_sent)
                    datasetSuccess.push('{{ $data_sms_sent['success'] }}');
                    datasetFail.push('{{ $data_sms_sent['fail'] }}');
                    labels.push('{{ $data_sms_sent['label'] }}');
                @endforeach
                var salesChart = new Chart(salesChartCanvas, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: datasetSuccess,
                            backgroundColor: gradient1,
                            borderColor: [
                                '#1bbd88'
                            ],
                            borderWidth: 2,
                            pointBorderColor: "#1bbd88",
                            pointBorderWidth: 4,
                            pointRadius: 1,
                            fill: 'origin',
                        },
                            {
                                data: datasetFail,
                                backgroundColor: gradient2,
                                borderColor: [
                                    '#ff420f'
                                ],
                                borderWidth: 2,
                                pointBorderColor: "#ff420f",
                                pointBorderWidth: 4,
                                pointRadius: 1,
                                fill: 'origin',
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            filler: {
                                propagate: false
                            }
                        },
                        scales: {
                            xAxes: [{
                                ticks: {
                                    fontColor: "#bababa"
                                },
                                gridLines: {
                                    display: false,
                                    drawBorder: false
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    fontColor: "#bababa",
                                    stepSize: 100,
                                    min: 0,
                                    // max: null
                                },
                                gridLines: {
                                    drawBorder: false,
                                    color: "rgba(101, 103, 119, 0.21)",
                                    zeroLineColor: "rgba(101, 103, 119, 0.21)"
                                }
                            }]
                        },
                        legend: {
                            display: false
                        },
                        tooltips: {
                            enabled: true
                        },
                        elements: {
                            line: {
                                tension: 0
                            }
                        },
                        legendCallback : function(chart) {
                            var text = [];
                            text.push('<div>');
                            text.push('<div class="d-flex align-items-center">');
                            text.push('<span class="bullet-rounded" style="border-color: ' + chart.data.datasets[0].borderColor[0] +' "></span>');
                            text.push('<p class="tx-12 text-muted mb-0 ml-2">Gửi thành công</p>');
                            text.push('</div>');
                            text.push('<div class="d-flex align-items-center">');
                            text.push('<span class="bullet-rounded" style="border-color: ' + chart.data.datasets[1].borderColor[0] +' "></span>');
                            text.push('<p class="tx-12 text-muted mb-0 ml-2">Gửi thất bại</p>');
                            text.push('</div>');
                            text.push('</div>');
                            return text.join('');
                        },
                    }
                });
                document.getElementById('sales-legend').innerHTML = salesChart.generateLegend();
            }
        });
    </script>
@endpush
