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
							<td class="text-right">{{ lang_trans('column_action') }}</td>
						</tr>
						</thead>
						<tbody>
						@if($customer_groups)
							@foreach($customer_groups as $customer_group)
								<tr>
									<td class="text-center">
										@if(in_array($customer_group['id'], $selected))
											<input class="minimal check" type="checkbox" name="selected[]" value="{{ $customer_group['id'] }}" checked="checked"/>
										@else
											<input class="minimal check" type="checkbox" name="selected[]" value="{{ $customer_group['id'] }}"/>
										@endif
									</td>
									<td class="text-left">{{ $customer_group['name'] }}</td>
									<td class="text-center">{{ $customer_group['sort_order'] }}</td>
									<td class="text-right">
										<a href="{{ $customer_group['edit'] }}" data-toggle="tooltip" title="{{ lang_trans('button_edit') }}" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
									</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td class="text-center" colspan="4">{{ lang_trans('text_no_results') }}</td>
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
