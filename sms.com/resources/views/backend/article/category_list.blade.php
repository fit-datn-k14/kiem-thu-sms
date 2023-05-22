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
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label" for="input-name">{{ lang_trans('entry_name') }}</label>
							<input type="text" name="filter_name" value="{{ $filter_name }}" placeholder="{{ lang_trans('entry_name') }}" id="input-name" class="form-control"/>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label" for="input-status">{{ lang_trans('entry_status') }}</label>
							<select name="filter_status" id="input-status" class="form-control">
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
					<div class="col-sm-4">
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
							<td class="text-left">{{ lang_trans('column_name') }}</td>
							<td class="text-center">{{ lang_trans('column_sort_order') }}</td>
							<td class="text-center">{{ lang_trans('column_status') }}</td>
							<td class="text-right">{{ lang_trans('column_action') }}</td>
						</tr>
						</thead>
						<tbody>
						@if($categories)
							@foreach($categories as $category)
								<tr>
									<td class="text-center">
										@if(in_array($category['id'], $selected))
											<input class="minimal check" type="checkbox" name="selected[]" value="{{ $category['id'] }}" checked="checked"/>
										@else
											<input class="minimal check" type="checkbox" name="selected[]" value="{{ $category['id'] }}"/>
										@endif
									</td>
									<td class="text-left">{{ $category['name'] }}</td>
									<td class="text-center">{{ $category['sort_order'] }}</td>
									<td class="text-center">
										<a class="{{ $category['status'] ? 'enabled' : 'disabled' }}" data-ajax="status" _status="{{ $category['id'] }},{{ $category['status'] }}" data-toggle="tooltip" title="{{ $category['status'] ? lang_trans('text_disabled') : lang_trans('text_enabled') }}">
											@if($category['status'])
												<i class="fa fa-check-circle"></i>
											@else
												<i class="fa fa-times-circle"></i>
											@endif
										</a>
									</td>
									<td class="text-right">
										<a href="{{ $category['edit'] }}" data-toggle="tooltip" title="{{ lang_trans('button_edit') }}" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
									</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td class="text-center" colspan="5">{{ lang_trans('text_no_results') }}</td>
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
        changeAjaxStatus('{{ site_url('article/category/ajax-status') }}', 'category_id');
        //--></script>
	<script type="text/javascript"><!--
        $('#button-filter').on('click', function () {
            var url = '';

            var filter_name = $('input[name=\'filter_name\']').val();
            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }
            var filter_status = $('select[name=\'filter_status\']').val();
            if (filter_status !== '') {
                url += '&filter_status=' + encodeURIComponent(filter_status);
            }

            if (url !== '') {
                location = '{{ site_url('article/category') }}?' + url;
            } else {
                location = '{{ site_url('article/category') }}';
            }
        });
        //--></script>
@endpush
