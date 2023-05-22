@extends('backend.layout.master')
@section('heading_title',lang_trans('text_heading'))
@section('content')
    <div class="box box-success color-palette-box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-cogs"></i> {{ lang_trans('text_heading') }}</h3>
            <div class="pull-right">
                <a onclick="$('#form').submit();" type="button" class="btn btn-primary btn-sm" >
                    <i class="fa fa-save"></i> {{ lang_trans('button_save') }}</a>
            </div>
        </div>
        <div class="box-body">

        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
@endsection