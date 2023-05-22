@extends('frontend.layout.master')
@section('heading_title', lang_trans('heading_title'))
@section('content')
    <div class="mdc-layout-grid__inner">
        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
            <div class="mdc-card p-0">
                <div class="card-padding">
                    <div class="mdc-layout-grid__inner">
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6">
                            <h6 class="card-title">{{ lang_trans('heading_title') }}</h6>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6 df-flex-end">
                            <div class="action">
                                <a onclick="confirm('{{ lang_trans('text_confirm') }}') ? $('#form').submit() : false;" class="mdc-button mdc-button--outlined outlined-button--secondary mdc-button--dense"><i class="material-icons mdc-button__icon">delete</i> {{ lang_trans('button_delete') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <form action="{{ $delete }}" method="post" enctype="multipart/form-data" id="form">
                        {{ csrf_field() }}
                        <div class="table-responsive">
                            <table class="table table-striped table-hoverable">
                                <thead>
                                <tr>
                                    <th style="width: 1px;" class="text-left">
                                        <div class="mdc-form-field">
                                            <div class="mdc-checkbox">
                                                <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" class="mdc-checkbox__native-control"/>
                                                <div class="mdc-checkbox__background">
                                                    <svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
                                                        <path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                                    </svg>
                                                    <div class="mdc-checkbox__mixedmark"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                    <th style="width: 1px;" class="text-center">{{ lang_trans('column_order_number') }}</th>
                                    <th class="text-center">
                                        @if($sort == 'created_at')
                                            <a href="{{ $sort_created_at }}" class="table-column-order">{{ lang_trans('column_created_at') }} <i class="material-icons">{{ $order == 'asc' ? 'arrow_drop_up' : 'arrow_drop_down' }}</i></a>
                                        @else
                                            <a href="{{ $sort_created_at }}">{{ lang_trans('column_created_at') }}</a>
                                        @endif
                                    </th>
                                    <th class="text-center">{{ lang_trans('column_total_sms_sent') }}</th>
                                    <th class="text-center">{{ lang_trans('column_total_sms_success') }}</th>
                                    <th class="text-center">{{ lang_trans('column_total_sms_fail') }}</th>
                                    <th class="text-right">{{ lang_trans('column_action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($smss)
                                    @foreach($smss as $sms)
                                        <tr>
                                            <td class="text-left">
                                                <div class="mdc-form-field">
                                                    <div class="mdc-checkbox">
                                                        @if(in_array($sms['id'], $selected))
                                                            <input type="checkbox" name="selected[]" value="{{ $sms['id'] }}" checked class="mdc-checkbox__native-control mdc-checkbox__sms-id"/>
                                                        @else
                                                            <input type="checkbox" name="selected[]" value="{{ $sms['id'] }}" class="mdc-checkbox__native-control mdc-checkbox__sms-id"/>
                                                        @endif
                                                        <div class="mdc-checkbox__background">
                                                            <svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
                                                                <path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                                            </svg>
                                                            <div class="mdc-checkbox__mixedmark"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="width: 1px;" class="text-center">{{ $sms['order_number'] }}</td>
                                            <td class="text-center">{{ $sms['created_at'] }}</td>
                                            <td class="text-center">{{ $sms['total_sms'] }}</td>
                                            <td class="text-center">{{ $sms['total_success'] }}</td>
                                            <td class="text-center">{{ $sms['total_fail'] }}</td>
                                            <td class="text-right">
                                                <div class="table-actions">
                                                    <div class="action">
                                                        <a href="{{ $sms['info'] }}" class="mdc-button mdc-button--outlined icon-button mdc-button--dense" data-toggle="tooltip" title="{{ lang_trans('button_info') }}"><i class="material-icons mdc-button__icon">forum</i></a>
                                                        <a onclick="confirm('{{ lang_trans('text_confirm') }}') ? location = '{{ $sms['delete'] }}' : false;" href="javascript:void(0)" class="mdc-button mdc-button--outlined icon-button mdc-button--dense outlined-button--secondary" data-toggle="tooltip" title="{{ lang_trans('button_delete') }}"><i class="material-icons mdc-button__icon">clear</i></a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="7">{{ lang_trans('text_no_results') }}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </form>
                    <div class="box-paginate">
                        {!! $pagination !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
