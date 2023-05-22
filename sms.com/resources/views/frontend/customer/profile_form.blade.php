@extends('frontend.layout.master')
@section('heading_title', lang_trans('heading_title'))
@section('content')
    <div class="mdc-layout-grid__inner">
        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
            <div class="mdc-card p-0">
                <div class="card-padding">
                    <div class="mdc-layout-grid__inner">
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-5">
                            <h6 class="card-title">{{ lang_trans('heading_title') }}</h6>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-7 df-flex-end">
                            <div class="action">
                                <a onclick="$('input[name=\'_redirect\']').val('edit');$('#form').attr('action', '{{ $action }}').submit()" class="mdc-button mdc-button--outlined mdc-button--dense"><i class="material-icons mdc-button__icon">done_outline</i> {{ lang_trans('button_save_edit') }}
                                </a>
                                <a onclick="$('input[name=\'_redirect\']').val('exit');$('#form').attr('action', '{{ $action }}').submit()" class="mdc-button mdc-button--outlined mdc-button--dense"><i class="material-icons mdc-button__icon">done_outline</i> {{ lang_trans('button_save_exit') }}
                                </a>
                                <a href="{{ $back }}" class="mdc-button mdc-button--unelevated filled-button--secondary mdc-button--dense"><i class="material-icons mdc-button__icon">reply</i> {{ lang_trans('button_back') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="mdc-card">
                        <form action="" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
                            {{ csrf_field() }}
                            <input type="hidden" name="_redirect" value="">
                            <div class="mdc-layout-grid__inner form-group">
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2">
                                    <div class="form-group-label required">{{ lang_trans('entry_full_name') }}</div>
                                </div>
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-10">
                                    <div class="required-wrapper">
                                        <div class="mdc-text-field mdc-text-field--outlined">
                                            <input type="text" class="mdc-text-field__input" id="input-full-name" required name="full_name" value="{{ $full_name }}">
                                            <div class="mdc-notched-outline">
                                                <div class="mdc-notched-outline__leading"></div>
                                                <div class="mdc-notched-outline__notch">
                                                    <label for="input-full-name" class="mdc-floating-label">{{ lang_trans('entry_full_name') }}</label>
                                                </div>
                                                <div class="mdc-notched-outline__trailing"></div>
                                            </div>
                                        </div>
                                        @if($errors->has('full_name'))
                                            <div class="text-danger">{{ $errors->first('full_name') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="mdc-layout-grid__inner form-group">
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2">
                                    <div class="form-group-label">{{ lang_trans('entry_email') }}</div>
                                </div>
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-10">
                                    <div class="required-wrapper">
                                        <div class="mdc-text-field mdc-text-field--outlined">
                                            <input type="text" class="mdc-text-field__input" id="input-email" name="email" disabled="disabled" value="{{ $email }}">
                                            <div class="mdc-notched-outline">
                                                <div class="mdc-notched-outline__leading"></div>
                                                <div class="mdc-notched-outline__notch">
                                                    <label for="input-email" class="mdc-floating-label">{{ lang_trans('entry_email') }}</label>
                                                </div>
                                                <div class="mdc-notched-outline__trailing"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mdc-layout-grid__inner form-group">
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2">
                                    <div class="form-group-label">{{ lang_trans('entry_password') }}</div>
                                </div>
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-10">
                                    <div class="required-wrapper">
                                        <div class="mdc-text-field mdc-text-field--outlined">
                                            <input type="password" class="mdc-text-field__input" id="input-password" name="password" value="{{ $password }}">
                                            <div class="mdc-notched-outline">
                                                <div class="mdc-notched-outline__leading"></div>
                                                <div class="mdc-notched-outline__notch">
                                                    <label for="input-password" class="mdc-floating-label">{{ lang_trans('entry_password') }}</label>
                                                </div>
                                                <div class="mdc-notched-outline__trailing"></div>
                                            </div>
                                        </div>
                                        @if($errors->has('password'))
                                            <div class="text-danger">{{ $errors->first('password') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="mdc-layout-grid__inner form-group">
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2">
                                    <div class="form-group-label">{{ lang_trans('entry_password_re_enter') }}</div>
                                </div>
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-10">
                                    <div class="required-wrapper">
                                        <div class="mdc-text-field mdc-text-field--outlined">
                                            <input type="password" class="mdc-text-field__input" id="input-password-re-enter" name="password_re_enter" value="{{ $password_re_enter }}">
                                            <div class="mdc-notched-outline">
                                                <div class="mdc-notched-outline__leading"></div>
                                                <div class="mdc-notched-outline__notch">
                                                    <label for="input-password-re-enter" class="mdc-floating-label">{{ lang_trans('entry_password_re_enter') }}</label>
                                                </div>
                                                <div class="mdc-notched-outline__trailing"></div>
                                            </div>
                                        </div>
                                        @if($errors->has('password_re_enter'))
                                            <div class="text-danger">{{ $errors->first('password_re_enter') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="mdc-layout-grid__inner form-group">
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2">
                                    <div class="form-group-label">{{ lang_trans('entry_sms_prefix') }}</div>
                                </div>
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-10">
                                    <div class="required-wrapper">
                                        <div class="mdc-text-field mdc-text-field--outlined">
                                            <input type="text" maxlength="32" class="mdc-text-field__input" id="input-sms-prefix" name="sms_prefix" value="{{ $sms_prefix }}">
                                            <div class="mdc-notched-outline">
                                                <div class="mdc-notched-outline__leading"></div>
                                                <div class="mdc-notched-outline__notch">
                                                    <label for="input-sms-prefix" class="mdc-floating-label">{{ lang_trans('entry_sms_prefix') }}</label>
                                                </div>
                                                <div class="mdc-notched-outline__trailing"></div>
                                            </div>
                                        </div>
                                        @if($errors->has('sms_prefix'))
                                            <div class="text-danger">{{ $errors->first('sms_prefix') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
