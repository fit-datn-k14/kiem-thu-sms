@extends('frontend.layout.master')
@section('heading_title', lang_trans('heading_title'))
@section('content')
    <div class="mdc-layout-grid__inner">
        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
            <div class="mdc-card p-0">
                <div class="card-padding">
                    <div class="mdc-layout-grid__inner">
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-5">
                            <h6 class="card-title">{{ $is_add ? lang_trans('text_add') : lang_trans('text_edit') }}</h6>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-7 df-flex-end">
                            <div class="action">
                                <a onclick="$('input[name=\'_redirect\']').val('add');$('#form').attr('action', '{{ $action }}').submit()" class="mdc-button mdc-button--outlined mdc-button--dense"><i class="material-icons mdc-button__icon">done_outline</i> {{ lang_trans('button_save_add') }}
                                </a>
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
                                    <div class="form-group-label">{{ lang_trans('entry_address') }}</div>
                                </div>
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-10">
                                    <div class="required-wrapper">
                                        <div class="mdc-text-field mdc-text-field--outlined">
                                            <textarea class="mdc-text-field__input" id="input-address" name="address" rows="2">{{ $address }}</textarea>
                                            <div class="mdc-notched-outline">
                                                <div class="mdc-notched-outline__leading"></div>
                                                <div class="mdc-notched-outline__notch">
                                                    <label for="input-address" class="mdc-floating-label">{{ lang_trans('entry_address') }}</label>
                                                </div>
                                                <div class="mdc-notched-outline__trailing"></div>
                                            </div>
                                        </div>
                                        @if($errors->has('address'))
                                            <div class="text-danger">{{ $errors->first('address') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="mdc-layout-grid__inner form-group">
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2">
                                    <div class="form-group-label required">{{ lang_trans('entry_phone') }}</div>
                                </div>
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-10">
                                    <div class="required-wrapper">
                                        <div class="mdc-text-field mdc-text-field--outlined">
                                            <input type="text" class="mdc-text-field__input" id="input-phone" required name="phone" value="{{ $phone }}">
                                            <div class="mdc-notched-outline">
                                                <div class="mdc-notched-outline__leading"></div>
                                                <div class="mdc-notched-outline__notch">
                                                    <label for="input-phone" class="mdc-floating-label">{{ lang_trans('entry_phone') }}</label>
                                                </div>
                                                <div class="mdc-notched-outline__trailing"></div>
                                            </div>
                                        </div>
                                        @if($errors->has('phone'))
                                            <div class="text-danger">{{ $errors->first('phone') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="mdc-layout-grid__inner form-group">
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2">
                                    <div class="form-group-label">{{ lang_trans('entry_note') }}</div>
                                </div>
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-10">
                                    <div class="required-wrapper">
                                        <div class="mdc-text-field mdc-text-field--outlined">
                                            <textarea class="mdc-text-field__input" id="input-note" name="note" rows="2">{{ $note }}</textarea>
                                            <div class="mdc-notched-outline">
                                                <div class="mdc-notched-outline__leading"></div>
                                                <div class="mdc-notched-outline__notch">
                                                    <label for="input-note" class="mdc-floating-label">{{ lang_trans('entry_note') }}</label>
                                                </div>
                                                <div class="mdc-notched-outline__trailing"></div>
                                            </div>
                                        </div>
                                        @if($errors->has('note'))
                                            <div class="text-danger">{{ $errors->first('note') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="mdc-layout-grid__inner form-group">
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2">
                                    <div class="form-group-label">{{ lang_trans('entry_sort_order') }}</div>
                                </div>
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-10">
                                    <div class="mdc-text-field mdc-text-field--outlined">
                                        <input type="number" class="mdc-text-field__input" id="input-sort-order" name="sort_order" value="{{ $sort_order }}">
                                        <div class="mdc-notched-outline">
                                            <div class="mdc-notched-outline__leading"></div>
                                            <div class="mdc-notched-outline__notch">
                                                <label for="input-sort-order" class="mdc-floating-label">{{ lang_trans('entry_sort_order') }}</label>
                                            </div>
                                            <div class="mdc-notched-outline__trailing"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mdc-layout-grid__inner form-group">
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2">
                                    <div class="form-group-label">{{ lang_trans('entry_status') }}</div>
                                </div>
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-10">
                                    <div class="mdc-switch mdc-switch--checked" data-mdc-auto-init="MDCSwitch" style="margin-top: 14px;">
                                        <div class="mdc-switch__track"></div>
                                        <div class="mdc-switch__thumb-underlay">
                                            <div class="mdc-switch__thumb">
                                                @if ($status)
                                                    <input type="checkbox" name="status" value="1" class="mdc-switch__native-control" role="switch" checked>
                                                @else
                                                    <input type="checkbox" name="status" value="1" class="mdc-switch__native-control" role="switch">
                                                @endif
                                            </div>
                                        </div>
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
