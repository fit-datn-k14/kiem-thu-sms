@extends('backend.layout.master')
@section('heading_title', lang_trans('heading_title'))
@section('content')
    <div class="box box-primary color-palette-box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-folder-open"></i> {{ lang_trans('heading_title') }}</h3>
            <div class="pull-right">
                <a onclick="$('input[name=\'_redirect\']').val('add');$('#form').attr('action', '{{ $action }}').submit()" class="btn btn-primary btn-sm"><i class="fa fa-check"></i> {{ lang_trans('button_save_add') }}
                </a>
                <a onclick="$('input[name=\'_redirect\']').val('exit');$('#form').attr('action', '{{ $action }}').submit()" class="btn btn-primary btn-sm"><i class="fa fa-check"></i> {{ lang_trans('button_save_exit') }}
                </a>
                <a href="{{ $back }}" class="btn btn-success btn-sm"><i class="fa fa-reply"></i> {{ lang_trans('button_back') }}
                </a>
            </div>
        </div>
        <div class="box-body">
            <form action="" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" name="_redirect" value="">
                <div class="well well-sm">
                    <div class="row well-row">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <input type="text" name="filter_full_name" value="" placeholder="{{ lang_trans('entry_full_name') }}" id="input-full-name" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <input type="text" name="filter_email" value="" placeholder="{{ lang_trans('entry_email') }}" id="input-email" class="form-control"/>
                            </div>
                            @if($errors->has('customer_id'))
                                <p class="text-danger">{{ $errors->first('customer_id') }}</p>
                            @endif
                        </div>
                        <div class="col-sm-8">
                            <h4 style="margin: 0 0 5px 0;font-size: 16px;">{{ lang_trans('text_customer_info') }}</h4>
                            <input id="customer-info__id" type="hidden" name="customer_id" value="{{ $customer_id }}" class="form-control"/>
                            <input id="customer-info__current_money" type="hidden" name="customer_current_money" value="{{ $customer_info['money'] }}" class="form-control"/>
                            <div id="customer-info__full_name" style="margin-bottom: 2px;"><span>{{ lang_trans('entry_full_name') }}:</span> <strong>{{ $customer_info['full_name'] }}</strong></div>
                            <div id="customer-info__email" style="margin-bottom: 2px;"><span>{{ lang_trans('entry_email') }}:</span> <strong>{{ $customer_info['email'] }}</strong></div>
                            <div id="customer-info__current_money_format"><span>{{ lang_trans('text_customer_info_money') }}:</span> <strong>{{ format_currency($customer_info['money'], true) }}</strong></div>
                        </div>
                    </div>
                </div>
                <div class="form-group required">
                    <label class="col-sm-3 control-label" for="input-amount-paid">{{ lang_trans('entry_amount_paid') }}</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input type="number" name="amount_paid" value="{{ $amount_paid }}" placeholder="{{ lang_trans('entry_amount_paid') }}" id="input-amount-paid" class="form-control"/>
                            <div class="input-group-addon">đ</div>
                        </div>
                        @if($errors->has('amount_paid'))
                            <p class="text-danger">{{ $errors->first('amount_paid') }}</p>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ lang_trans('text_amount_received') }}</label>
                    <div class="col-sm-3">
                        <div id="customer-info__amount_received" style="padding-top: 7px;"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ lang_trans('text_amount_total') }}</label>
                    <div class="col-sm-3">
                        <div id="customer-info__amount_total" style="padding-top: 7px;"></div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box-body -->
    </div>
@endsection
@push('script_footer')
    <script type="text/javascript">
        var filterFullName = $('input[name=\'filter_full_name\']');
        var filterEmail = $('input[name=\'filter_email\']');
        var amountPaid = $('input[name=\'amount_paid\']');

        var customerId = $('#customer-info__id');
        var customerFullName = $('#customer-info__full_name');
        var customerEmail = $('#customer-info__email');
        var customerCurrentMoney = $('#customer-info__current_money');
        var customerCurrentMoneyFormat = $('#customer-info__current_money_format');
        var customerAmountReceived = $('#customer-info__amount_received');
        var customerAmountTotal = $('#customer-info__amount_total');

        var moneyRechargeConfig = @json($config_money_recharge);

        function getAmountReceived(amount) {
            var itemConfigs = Object.keys(moneyRechargeConfig);
            if (moneyRechargeConfig && itemConfigs.length && amount >= parseInt(itemConfigs[0])) {
                var getTotal = function (index) {
                    var total = amount + (amount * parseInt(moneyRechargeConfig[itemConfigs[index]]) / 100);
                    return {
                        total: total,
                        total_format: formatCurrency(total),
                    };
                };
                if (amount >= parseInt(itemConfigs[itemConfigs.length - 1])) {
                    return getTotal(itemConfigs.length - 1);
                }
                var itemConfigIndex = Object.keys(moneyRechargeConfig).findIndex(function (item) {
                    return parseInt(item) > amount;
                });
                if (itemConfigIndex > -1) {
                    return getTotal(itemConfigIndex - 1);
                }
                return {
                    total: amount,
                    total_format: formatCurrency(amount),
                };
            }
            return {
                total: amount,
                total_format: formatCurrency(amount),
            };
        }

        amountPaid.on('change keyup', function (e) {
            var value = e.target.value;
            var amountReceived = getAmountReceived(parseInt(value || 0));
            customerAmountReceived.text(amountReceived.total_format);
            customerAmountTotal.text(formatCurrency(amountReceived.total + parseInt(customerCurrentMoney.val() || 0)));
        });

        amountPaid.trigger('change');

        function updatePaymentInfo(item) {
            filterFullName.val('');
            filterEmail.val('');
            customerId.val(item['value']);
            customerFullName.find('strong').text(item['full_name']);
            customerEmail.find('strong').text(item['email']);
            customerCurrentMoneyFormat.find('strong').text(formatCurrency(parseInt(item['money'] || 0)));
            customerCurrentMoney.val(item['money'] || 0);
            amountPaid.trigger('change');
        }

        filterFullName.autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: '{{ site_url('customer/customer/auto-complete') }}?filter_full_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['full_name'],
                                value: item['id'],
                                full_name: item['full_name'],
                                email: item['email'],
                                money: item['money'],
                            }
                        }));
                    }
                });
            },
            'select': function (item) {
                updatePaymentInfo(item);
            }
        });
        filterEmail.autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: '{{ site_url('customer/customer/auto-complete') }}?filter_email=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['email'],
                                value: item['id'],
                                full_name: item['full_name'],
                                email: item['email'],
                                money: item['money'],
                            }
                        }));
                    }
                });
            },
            'select': function (item) {
                updatePaymentInfo(item);
            }
        });
    </script>
@endpush
