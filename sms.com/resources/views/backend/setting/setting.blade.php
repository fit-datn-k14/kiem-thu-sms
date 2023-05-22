@extends('backend.layout.master')
@section('heading_title', lang_trans('heading_title'))
@section('content')
    <div class="box box-primary color-palette-box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-cogs"></i> {{ lang_trans('heading_title') }}</h3>
            <div class="pull-right">
                <a onclick="$('#form').submit();" type="button" class="btn btn-primary btn-sm" >
                    <i class="fa fa-save"></i> {{ lang_trans('button_save') }}</a>
            </div>
        </div>
        <div class="box-body">
            <form action="{{ $action }}" method="post" enctype="multipart/form-data" class="form-horizontal" id="form">
                {{ csrf_field() }}
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-general" data-toggle="tab">{{ lang_trans('tab_general') }}</a></li>
                        <li><a href="#tab-seo" data-toggle="tab">{{ lang_trans('tab_seo') }}</a></li>
                        <li><a href="#tab-option" data-toggle="tab">{{ lang_trans('tab_option') }}</a></li>
                        <li><a href="#tab-money-config" data-toggle="tab">{{ lang_trans('tab_money_config') }}</a></li>
                        <li><a href="#tab-sms-setting" data-toggle="tab">{{ lang_trans('tab_sms_setting') }}</a></li>
                        <li><a href="#tab-email" data-toggle="tab">{{ lang_trans('tab_email') }}</a></li>
                        <li><a href="#tab-server" data-toggle="tab">{{ lang_trans('tab_server') }}</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active in" id="tab-general">
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-name">{{ lang_trans('entry_name') }}</label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_name" value="{{ $config_name }}"  id="input-name" class="form-control" />
                                    @if($errors->has('config_name'))
                                        <p class="text-danger">{{ $errors->first('config_name') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-address">{{ lang_trans('entry_address') }}</label>
                                <div class="col-sm-10">
                                    <textarea name="config_address"  rows="3" id="input-address" class="form-control">{{ $config_address }}</textarea>
                                    @if($errors->has('config_address'))
                                        <p class="text-danger">{{ $errors->first('config_address') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-email"><span class="required">*</span> {{ lang_trans('entry_email') }}</label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_email" value="{{ $config_email }}" placeholder="{{ lang_trans('entry_email') }}" id="input-email" class="form-control" />
                                    @if($errors->has('config_email'))
                                        <p class="text-danger">{{ $errors->first('config_email') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-telephone"><span class="required">*</span> {{ lang_trans('entry_telephone') }}</label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_telephone" value="{{ $config_telephone }}" placeholder="{{ lang_trans('entry_telephone') }}" id="input-telephone" class="form-control" />
                                    @if($errors->has('config_telephone'))
                                        <p class="text-danger">{{ $errors->first('config_telephone') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-seo">
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-meta-title">{{ lang_trans('entry_meta_title') }}</label>
                                <div class="col-sm-10">
                                    <input placeholder="{{ lang_trans('entry_meta_title') }}" type="text" name="config_meta_title" value="{{ $config_meta_title }}"  id="input-meta-title" class="form-control" />
                                    @if($errors->has('config_meta_title'))
                                        <p class="text-danger">{{ $errors->first('config_meta_title') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-meta-description">{{ lang_trans('entry_meta_description') }}</label>
                                <div class="col-sm-10">
                                    <textarea placeholder="{{ lang_trans('entry_meta_description') }}" name="config_meta_description"  rows="3" id="input-meta-description" class="form-control">{{ $config_meta_description }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-meta-keyword">{{ lang_trans('entry_meta_keyword') }}</label>
                                <div class="col-sm-10">
                                    <textarea placeholder="{{ lang_trans('entry_meta_keyword') }}" name="config_meta_keyword"  rows="3" id="input-meta-keyword" class="form-control">{{ $config_meta_keyword }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-option">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-limit-admin">{{ lang_trans('entry_limit_admin') }}</label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_limit_admin" value="{{ $config_limit_admin }}"  id="input-limit-admin" class="form-control" />
                                    @if($errors->has('config_limit_admin'))
                                        <p class="text-danger">{{ $errors->first('config_limit_admin') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-money-config">
                            <div class="form-group">
                                <label class="col-sm-1 control-label"></label>
                                <div class="col-sm-10">
                                    <strong>{{ lang_trans('entry_money_config') }}</strong>
                                </div>
                            </div>
                            @foreach($money_ranges as $money_range)
                            <div class="form-group">
                                <label class="col-sm-2 control-label">{{ format_currency($money_range, true) }}</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="number" name="config_money_recharge[{{ $money_range }}]" value="{{ $config_money_recharge[$money_range] ?? '' }}" placeholder="{{ lang_trans('entry_amount_percent') }}" class="form-control"/>
                                        <div class="input-group-addon">%</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="tab-pane fade" id="tab-sms-setting">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-sms-price">{{ lang_trans('entry_sms_price') }}</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="number" name="config_sms_price" value="{{ $config_sms_price }}" id="input-sms-price" class="form-control" />
                                        <div class="input-group-addon">/ sms</div>
                                    </div>
                                    @if($errors->has('config_sms_price'))
                                        <p class="text-danger">{{ $errors->first('config_sms_price') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-email"></div>
                        <div class="tab-pane fade" id="tab-server">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">{{ lang_trans('entry_maintenance') }}</label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        @if($config_maintenance)
                                            <input type="radio" class="minimal" name="config_maintenance" value="1" checked="checked" /> {{ lang_trans('text_enabled') }}
                                        @else
                                            <input type="radio" class="minimal" name="config_maintenance" value="1" /> {{ lang_trans('text_enabled') }}
                                        @endif
                                    </label> <label class="radio-inline">
                                        @if(!$config_maintenance)
                                            <input type="radio" class="minimal" name="config_maintenance" value="0" checked="checked" /> {{ lang_trans('text_disabled') }}
                                        @else
                                            <input type="radio" class="minimal" name="config_maintenance" value="0" /> {{ lang_trans('text_disabled') }}
                                        @endif
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">{{ lang_trans('entry_secure') }}</label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        @if($config_secure)
                                            <input type="radio" class="minimal" name="config_secure" value="1" checked="checked" /> {{ lang_trans('text_enabled') }}
                                        @else
                                            <input type="radio" class="minimal" name="config_secure" value="1" /> {{ lang_trans('text_enabled') }}
                                        @endif
                                    </label> <label class="radio-inline">
                                        @if(!$config_secure)
                                            <input type="radio" class="minimal" name="config_secure" value="0" checked="checked" /> {{ lang_trans('text_disabled') }}
                                        @else
                                            <input type="radio" class="minimal" name="config_secure" value="0" /> {{ lang_trans('text_disabled') }}
                                        @endif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
@endsection
@push('script_footer')
<script type="text/javascript">
    $('#general_tab a:first').tab('show');
    $('#seo_tab a:first').tab('show');
</script>
@endpush
