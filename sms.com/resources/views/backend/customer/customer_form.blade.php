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
                    <label class="col-sm-2 control-label" for="input-full-name">{{ lang_trans('entry_full_name') }}</label>
                    <div class="col-sm-10">
                        <input type="text" name="full_name" value="{{ $full_name }}" placeholder="{{ lang_trans('entry_full_name') }}" id="input-full-name" class="form-control"/>
                        @if($errors->has('full_name'))
                            <p class="text-danger">{{ $errors->first('full_name') }}</p>
                        @endif
                    </div>
                </div>
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-customer-group">{{ lang_trans('entry_customer_group') }}</label>
                    <div class="col-sm-10">
                        <select name="customer_group_id" id="input-customer-group" class="form-control">
                            @foreach($customer_groups as $customer_group)
                                @if($customer_group['id'] == $customer_group_id)
                                    <option value="{{ $customer_group['id'] }}" selected="selected">{{ $customer_group['name'] }}</option>
                                @else
                                    <option value="{{ $customer_group['id'] }}">{{ $customer_group['name'] }}</option>
                                @endif
                            @endforeach
                        </select>
                        @if($errors->has('customer_group_id'))
                            <p class="text-danger">{{ $errors->first('customer_group_id') }}</p>
                        @endif
                    </div>
                </div>
                <div class="form-group @if($is_add) required @endif">
                    <label class="col-sm-2 control-label" for="input-email">{{ lang_trans('entry_email') }}</label>
                    <div class="col-sm-10">
                        <input type="text" name="email" value="{{ $email }}" @if(!$is_add) disabled="disabled" @endif placeholder="{{ lang_trans('entry_email') }}" id="input-email" class="form-control"/>
                        @if($errors->has('email'))
                            <p class="text-danger">{{ $errors->first('email') }}</p>
                        @endif
                    </div>
                </div>
                <div class="form-group @if($is_add) required @endif">
                    <label class="col-sm-2 control-label" for="input-password">{{ lang_trans('entry_password') }}</label>
                    <div class="col-sm-10">
                        <input type="text" name="password" value="{{ $password }}" placeholder="{{ lang_trans('entry_password') }}" id="input-password" class="form-control"/>
                        @if($errors->has('password'))
                            <p class="text-danger">{{ $errors->first('password') }}</p>
                        @endif
                    </div>
                </div>
                <div class="form-group @if($is_add) required @endif">
                    <label class="col-sm-2 control-label" for="input-password-re-enter">{{ lang_trans('entry_password_re_enter') }}</label>
                    <div class="col-sm-10">
                        <input type="text" name="password_re_enter" value="{{ $password_re_enter }}" placeholder="{{ lang_trans('entry_password_re_enter') }}" id="input-password-re-enter" class="form-control"/>
                        @if($errors->has('password_re_enter'))
                            <p class="text-danger">{{ $errors->first('password_re_enter') }}</p>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ lang_trans('entry_image') }}</label>
                    <div class="col-sm-10">
                        <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail">
                            <img src="{{ $thumb }}" alt="" title="" data-placeholder="{{ $no_image }}"/>
                        </a>
                        <input type="hidden" name="image" value="{{ $image }}" id="input-image"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-sms-brand-name">{{ lang_trans('entry_sms_brand_name') }}</label>
                    <div class="col-sm-10">
                        <input type="text" maxlength="24" name="sms_brand_name" value="{{ $sms_brand_name }}" placeholder="{{ lang_trans('entry_sms_brand_name') }}" id="input-sms-brand-name" class="form-control"/>
                        @if($errors->has('sms_brand_name'))
                            <p class="text-danger">{{ $errors->first('sms_brand_name') }}</p>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-sms-prefix">{{ lang_trans('entry_sms_prefix') }}</label>
                    <div class="col-sm-10">
                        <input type="text" maxlength="32" name="sms_prefix" value="{{ $sms_prefix }}" placeholder="{{ lang_trans('entry_sms_prefix') }}" id="input-sms-prefix" class="form-control"/>
                        @if($errors->has('sms_prefix'))
                            <p class="text-danger">{{ $errors->first('sms_prefix') }}</p>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-sms-price">{{ lang_trans('entry_sms_price') }}</label>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input type="number" name="sms_price" value="{{ $sms_price }}" placeholder="{{ lang_trans('entry_sms_price') }}" id="input-sms-price" class="form-control" />
                            <div class="input-group-addon">/ sms</div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-status">{{ lang_trans('entry_status') }}</label>
                    <div class="col-sm-10">
                        @if ($status)
                            <label class="radio-inline">
                                <input type="radio" name="status" value="1" checked="checked"/> {{ lang_trans('text_enabled') }}
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="status" value="0"/> {{ lang_trans('text_disabled') }}
                            </label>
                        @else
                            <label class="radio-inline">
                                <input type="radio" name="status" value="1"/> {{ lang_trans('text_enabled') }}
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="status" value="0" checked="checked"/> {{ lang_trans('text_disabled') }}
                            </label>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-newsletter">{{ lang_trans('entry_newsletter') }}</label>
                    <div class="col-sm-10">
                        @if ($newsletter)
                            <label class="radio-inline">
                                <input type="radio" name="newsletter" value="1" checked="checked"/> {{ lang_trans('text_enabled') }}
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="newsletter" value="0"/> {{ lang_trans('text_disabled') }}
                            </label>
                        @else
                            <label class="radio-inline">
                                <input type="radio" name="newsletter" value="1"/> {{ lang_trans('text_enabled') }}
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="newsletter" value="0" checked="checked"/> {{ lang_trans('text_disabled') }}
                            </label>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box-body -->
    </div>
@endsection
