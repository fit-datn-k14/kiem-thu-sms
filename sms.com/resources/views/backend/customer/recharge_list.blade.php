@extends('backend.layout.master')
@section('heading_title', lang_trans('heading_title'))
@section('content')
	<div class="box box-primary color-palette-box">
		<div class="box-header with-border">
			<h3 class="box-title"><i class="fa fa-folder-open"></i> {{ lang_trans('heading_title') }}</h3>
			<div class="pull-right">
				<a href="{{ $add }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> {{ lang_trans('heading_title') }}
				</a>
				{{--<a onclick="confirm('{{ lang_trans('text_confirm') }}') ? $('#form').submit() : false;" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> {{ lang_trans('button_delete') }}
				</a>--}}
			</div>
		</div>
		<div class="box-body">
			<form action="{{ $delete }}" method="post" enctype="multipart/form-data" id="form">
				{{ csrf_field() }}
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
                        <div class="col-sm-1">
                            <div class="hidden-sm hidden-xs" style="margin-bottom:25px;"></div>
                            <button type="button" id="button-filter" class="btn btn-primary">
                                <i class="fa fa-filter"></i> {{ lang_trans('button_filter') }}</button>
                        </div>
                    </div>
                </div>
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped">
						<thead>
						<tr>
							{{--<td style="width: 1px;" class="text-center">
								<input class="minimal check-all" type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);"/>
							</td>--}}
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
                            <td class="text-right">{{ lang_trans('column_amount_paid') }}</td>
                            <td class="text-right">{{ lang_trans('column_amount_received') }}</td>
                            <td class="text-right">{{ lang_trans('column_amount_total') }}</td>
                            <td class="text-left">{{ lang_trans('column_payment_method') }}</td>
                            <td class="text-left">{{ lang_trans('column_ip') }}</td>
                            <td class="text-left">
                                @if($sort == 'created_at')
                                    <a href="{{ $sort_created_at }}">{{ lang_trans('column_created_at') }} <i class="fa fa-caret-{{ $order == 'asc' ? 'up' : 'down' }}"></i></a>
                                @else
                                    <a href="{{ $sort_created_at }}"></a>
                                @endif
                            </td>
						</tr>
						</thead>
						<tbody>
						@if($recharges)
							@foreach($recharges as $recharge)
								<tr>
									{{--<td class="text-center">
										@if(in_array($recharge['id'], $selected))
											<input class="minimal check" type="checkbox" name="selected[]" value="{{ $recharge['id'] }}" checked="checked"/>
										@else
											<input class="minimal check" type="checkbox" name="selected[]" value="{{ $recharge['id'] }}"/>
										@endif
									</td>--}}
                                    <td class="text-left">{{ $recharge['full_name'] }}</td>
                                    <td class="text-left">{{ $recharge['email'] }}</td>
                                    <td class="text-right">{{ format_currency($recharge['amount_paid']) }}</td>
                                    <td class="text-right">{{ format_currency($recharge['amount_received']) }}</td>
                                    <td class="text-right">{{ format_currency($recharge['amount_total']) }}</td>
                                    <td class="text-left">{{ $recharge['payment_method'] }}</td>
                                    <td class="text-left">{{ $recharge['ip'] }}</td>
                                    <td class="text-left">{{ $recharge['created_at'] }}</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td class="text-center" colspan="8">{{ lang_trans('text_no_results') }}</td>
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
        $('#button-filter').on('click', function () {
            var url = '';

            var filter_full_name = $('input[name=\'filter_full_name\']').val();
            if (filter_full_name) {
                url += '&filter_full_name=' + encodeURIComponent(filter_full_name);
            }

            var filter_email = $('input[name=\'filter_email\']').val();
            if (filter_email) {
                url += '&filter_email=' + encodeURIComponent(filter_email);
            }

            if  (url !== ''){
                location = '{{ site_url('customer/recharge') }}?' + url;
            }else{
                location = '{{ site_url('customer/recharge') }}';
            }
        });
        //--></script>
    <script type="text/javascript">
        $('input[name=\'filter_full_name\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: '{{ site_url('customer/recharge/auto-complete') }}?filter_full_name=' + encodeURIComponent(request),
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
                    url: '{{ site_url('customer/recharge/auto-complete') }}?filter_email=' + encodeURIComponent(request),
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
