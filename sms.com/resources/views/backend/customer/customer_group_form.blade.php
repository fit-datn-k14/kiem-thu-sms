@extends('backend.layout.master')
@section('heading_title', lang_trans('heading_title'))
@section('content')
    <div class="box box-primary color-palette-box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-folder-open"></i> {{ lang_trans('heading_title') }}</h3>
            <div class="pull-right">
                <a onclick="$('input[name=\'_redirect\']').val('add');$('#form').attr('action', '{{ $action }}').submit()" class="btn btn-primary btn-sm"><i class="fa fa-check"></i> {{ lang_trans('button_save_add') }}
                </a>
                <a onclick="$('input[name=\'_redirect\']').val('edit');$('#form').attr('action', '{{ $action }}').submit()" class="btn btn-primary btn-sm"><i class="fa fa-check"></i> {{ lang_trans('button_save_edit') }}
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
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-name">{{ lang_trans('entry_name') }}</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" value="{{ $name }}" placeholder="{{ lang_trans('entry_name') }}" id="input-name" class="form-control"/>
                        @if($errors->has('name'))
                            <p class="text-danger">{{ $errors->first('name') }}</p>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-sort-order">{{ lang_trans('entry_sort_order') }}</label>
                    <div class="col-sm-10">
                        <input type="text" name="sort_order" value="{{ $sort_order }}" placeholder="{{ lang_trans('entry_sort_order') }}" id="input-sort-order" class="form-control"/>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box-body -->
    </div>
@endsection
