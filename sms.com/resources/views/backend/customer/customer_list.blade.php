@extends('backend.layout.master')
@section('heading_title', lang_trans('heading_title'))
@section('content')
    <div class="box box-primary color-palette-box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-folder-open"></i> {{ lang_trans('heading_title') }}</h3>
            <div class="pull-right">
                <a href="{{ $add }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> {{ lang_trans('button_add') }}
                </a>
                <a onclick="confirm('{{ lang_trans('text_confirm') }}') ? $('#form').submit() : false;" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> {{ lang_trans('button_delete') }}
                </a>
            </div>
        </div>
        <div class="box-body">
            <div class="well well-sm">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label" for="input-full-name">{{ lang_trans('entry_full_name') }}</label>
                            <input type="text" name="filter_full_name" value="{{ $filter_full_name }}" placeholder="{{ lang_trans('entry_full_name') }}" id="input-full-name" class="form-control"/>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label" for="input-email">{{ lang_trans('entry_email') }}</label>
                            <input type="text" name="filter_email" value="{{ $filter_email }}" placeholder="{{ lang_trans('entry_email') }}" id="input-email" class="form-control"/>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="row">
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <label class="control-label" for="input-group-id">{{ lang_trans('entry_customer_group') }}</label>
                                    <select name="filter_customer_group_id" id="input-group-id" class="form-control">
                                        <option value=""></option>
                                        @foreach($customer_groups as $customer_group)
                                            @if($customer_group['id'] == $filter_customer_group_id)
                                                <option value="{{ $customer_group['id'] }}" selected="selected">{{ $customer_group['name'] }}</option>
                                            @else
                                                <option value="{{ $customer_group['id'] }}">{{ $customer_group['name'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label class="control-label" for="input-status">{{ lang_trans('column_status') }}</label>
                                    <select name="filter_status" class="form-control">
                                        <option value=""></option>
                                        @if($filter_status == '1')
                                            <option value="1" selected="selected">{{ lang_trans('text_enabled') }}</option>
                                        @else
                                            <option value="1">{{ lang_trans('text_enabled') }}</option>
                                        @endif
                                        @if($filter_status == '0')
                                            <option value="0" selected="selected">{{ lang_trans('text_disabled') }}</option>
                                        @else
                                            <option value="0">{{ lang_trans('text_disabled') }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="hidden-sm hidden-xs" style="margin-bottom:25px;"></div>
                        <button type="button" id="button-filter" class="btn btn-primary">
                            <i class="fa fa-filter"></i> {{ lang_trans('button_filter') }}</button>
                    </div>
                </div>
            </div>
            <form action="{{ $delete }}" method="post" enctype="multipart/form-data" id="form">
                {{ csrf_field() }}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <td style="width: 1px;" class="text-center">
                                <input class="minimal check-all" type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);"/>
                            </td>
                            <td class="text-left">
                                @if($sort == 'full_name')
                                    <a href="{{ $sort_full_name }}">{{ lang_trans('column_full_name') }} <i class="fa fa-caret-{{ $order == 'asc' ? 'up' : 'down' }}"></i></a>
                                @else
                                    <a href="{{ $sort_full_name }}">{{ lang_trans('column_full_name') }}</a>
                                @endif
                            </td>
                            <td class="text-left">
                                @if($sort == 'email')
                                    <a href="{{ $sort_email }}">{{ lang_trans('column_email') }} <i class="fa fa-caret-{{ $order == 'asc' ? 'up' : 'down' }}"></i></a>
                                @else
                                    <a href="{{ $sort_email }}">{{ lang_trans('column_email') }}</a>
                                @endif
                            </td>
                            <td class="text-right">
                                @if($sort == 'money')
                                    <a href="{{ $sort_money }}">{{ lang_trans('column_money') }} <i class="fa fa-caret-{{ $order == 'asc' ? 'up' : 'down' }}"></i></a>
                                @else
                                    <a href="{{ $sort_money }}">{{ lang_trans('column_money') }}</a>
                                @endif
                            </td>
                            <td class="text-left">{{ lang_trans('column_sms_brand_name') }}</td>
                            <td class="text-left">{{ lang_trans('column_sms_prefix') }}</td>
                            <td class="text-right">
                                @if($sort == 'sms_price')
                                    <a href="{{ $sort_sms_price }}">{{ lang_trans('column_sms_price') }} <i class="fa fa-caret-{{ $order == 'asc' ? 'up' : 'down' }}"></i></a>
                                @else
                                    <a href="{{ $sort_sms_price }}">{{ lang_trans('column_sms_price') }}</a>
                                @endif
                            </td>
                            <td class="text-left">{{ lang_trans('column_customer_group') }}</td>
                            <td class="text-right">
                                @if($sort == 'total_sms')
                                    <a href="{{ $sort_total_sms }}">{{ lang_trans('column_total_sms') }} <i class="fa fa-caret-{{ $order == 'asc' ? 'up' : 'down' }}"></i></a>
                                @else
                                    <a href="{{ $sort_total_sms }}">{{ lang_trans('column_total_sms') }}</a>
                                @endif
                            </td>
                            <td class="text-left">{{ lang_trans('column_ip') }}</td>
                            <td class="text-left">
                                @if($sort == 'created_at')
                                    <a href="{{ $sort_created_at }}">{{ lang_trans('column_created_at') }} <i class="fa fa-caret-{{ $order == 'asc' ? 'up' : 'down' }}"></i></a>
                                @else
                                    <a href="{{ $sort_created_at }}">{{ lang_trans('column_created_at') }}</a>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($sort == 'status')
                                    <a href="{{ $sort_status }}">{{ lang_trans('column_status') }} <i class="fa fa-caret-{{ $order == 'asc' ? 'up' : 'down' }}"></i></a>
                                @else
                                    <a href="{{ $sort_status }}">{{ lang_trans('column_status') }}</a>
                                @endif
                            </td>
                            <td class="text-right">{{ lang_trans('column_action') }}</td>
                        </tr>
                        </thead>
                        <tbody>
                        @if($customers)
                            @foreach($customers as $customer)
                                <tr>
                                    <td class="text-center">
                                        @if(in_array($customer['id'], $selected))
                                            <input class="minimal check" type="checkbox" name="selected[]" value="{{ $customer['id'] }}" checked="checked"/>
                                        @else
                                            <input class="minimal check" type="checkbox" name="selected[]" value="{{ $customer['id'] }}"/>
                                        @endif
                                    </td>
                                    <td class="text-left">{{ $customer['full_name'] }}</td>
                                    <td class="text-left">{{ $customer['email'] }}</td>
                                    <td class="text-right">{{ $customer['money'] }}</td>
                                    <td class="text-left">{{ $customer['sms_brand_name'] }}</td>
                                    <td class="text-left">{{ $customer['sms_prefix'] }}</td>
                                    <td class="text-right">{{ $customer['sms_price'] }}</td>
                                    <td class="text-left">{{ $customer['customer_group'] }}</td>
                                    <td class="text-right">{{ $customer['total_sms'] }}</td>
                                    <td class="text-left">{{ $customer['ip'] }}</td>
                                    <td class="text-left">{{ $customer['created_at'] }}</td>
                                    <td class="text-center">
                                        <a class="{{ $customer['status'] ? 'enabled' : 'disabled' }}" data-ajax="status" _status="{{ $customer['id'] }},{{ $customer['status'] }}" data-toggle="tooltip" title="{{ $customer['status'] ? lang_trans('text_enabled') : lang_trans('text_disabled') }}">
                                            @if($customer['status'])
                                                <i class="fa fa-check-circle"></i>
                                            @else
                                                <i class="fa fa-times-circle"></i>
                                            @endif
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ $customer['edit'] }}" data-toggle="tooltip" title="{{ lang_trans('button_edit') }}" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="13">{{ lang_trans('text_no_results') }}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </form>
            <div class="paginate">
                {!! $pagination !!}
            </div>
        </div>
        <!-- /.box-body -->
    </div>
@endsection
@push('script_footer')
    <script type="text/javascript"><!--
        changeAjaxStatus('{{ site_url('customer/customer/ajax-status') }}', 'customer_id');
        //--></script>
    <script type="text/javascript"><!--
        $('#button-filter').on('click', function () {
            var url = '';

            var filter_full_name = $('input[name=\'filter_full_name\']').val();
            if (filter_full_name) {
                url += '&filter_full_name=' + encodeURIComponent(filter_full_name);
            }
            var filter_status = $('select[name=\'filter_status\']').val();
            if (filter_status !== '') {
                url += '&filter_status=' + encodeURIComponent(filter_status);
            }

            var filter_email = $('input[name=\'filter_email\']').val();
            if (filter_email) {
                url += '&filter_email=' + encodeURIComponent(filter_email);
            }

            var filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').val();
            if (filter_customer_group_id !== '') {
                url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
            }
            if  (url !== ''){
                location = '{{ site_url('customer/customer') }}?' + url;
            }else{
                location = '{{ site_url('customer/customer') }}';
            }
        });
        //--></script>
    <script type="text/javascript">
        $('input[name=\'filter_full_name\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: '{{ site_url('customer/customer/auto-complete') }}?filter_full_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['full_name'],
                                value: item['id']
                            }
                        }));
                    }
                });
            },
            'select': function (item) {
                $('input[name=\'filter_full_name\']').val(item['label']);
            }
        });
        $('input[name=\'filter_email\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: '{{ site_url('customer/customer/auto-complete') }}?filter_email=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['email'],
                                value: item['id']
                            }
                        }));
                    }
                });
            },
            'select': function (item) {
                $('input[name=\'filter_email\']').val(item['label']);
            }
        });
        //--></script>
@endpush
