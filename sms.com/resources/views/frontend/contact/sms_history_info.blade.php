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
                                <a href="{{ $back }}" class="mdc-button mdc-button--unelevated filled-button--secondary mdc-button--dense"><i class="material-icons mdc-button__icon">reply</i> {{ lang_trans('button_back') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hoverable">
                            <thead>
                            <tr>
                                <th style="width: 1px;" class="text-center">{{ lang_trans('column_order_number') }}</th>
                                <th class="text-left">{{ lang_trans('column_full_name') }}</th>
                                <th class="text-left">{{ lang_trans('column_phone') }}</th>
                                <th class="text-left">{{ lang_trans('column_content') }}</th>
                                <th class="text-left">{{ lang_trans('column_total_sms') }}</th>
                                <th class="text-left">{{ lang_trans('column_is_success') }}</th>
                                <th class="text-left">{{ lang_trans('column_msg') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($sms_logs)
                                @foreach($sms_logs as $sms_log)
                                    <tr>
                                        <td style="width: 1px;" class="text-center">{{ $sms_log['order_number'] }}</td>
                                        <td class="text-left">{{ $sms_log['full_name'] }}</td>
                                        <td class="text-left">{{ $sms_log['phone'] }}</td>
                                        <td class="text-left sms-log-content">
                                            <div class="log">{{ $sms_log['content'] }}</div>
                                        </td>
                                        <td class="text-left">{{ $sms_log['total_sms'] }}</td>
                                        <td class="text-left">{{ $sms_log['is_success'] ? lang_trans('text_sms_success') : lang_trans('text_sms_error') }}</td>
                                        <td class="text-left">{{ $sms_log['msg'] }}</td>
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
                    <div class="box-paginate">
                        {!! $pagination !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
