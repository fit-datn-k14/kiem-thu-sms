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
                            <div>{{ lang_trans('text_money_remain') }} <strong>{{ format_currency($customer_money, true) }}</strong></div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hoverable">
                            <thead>
                            <tr>
                                <th style="width: 1px;" class="text-center">{{ lang_trans('column_order_number') }}</th>
                                <th class="text-left">{{ lang_trans('column_created_at') }}</th>
                                <th class="text-left">{{ lang_trans('column_payment_method') }}</th>
                                <th class="text-right">{{ lang_trans('column_amount_paid') }}</th>
                                <th class="text-right">{{ lang_trans('column_amount_received') }}</th>
                                <th class="text-right">{{ lang_trans('column_amount_total') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($recharges)
                                @foreach($recharges as $recharge)
                                    <tr>
                                        <td style="width: 1px;" class="text-center">{{ $recharge['order_number'] }}</td>
                                        <td class="text-left">{{ $recharge['created_at'] }}</td>
                                        <td class="text-left">{{ $recharge['payment_method'] }}</td>
                                        <td class="text-right">{{ format_currency($recharge['amount_paid'], true) }}</td>
                                        <td class="text-right">{{ format_currency($recharge['amount_received'], true) }}</td>
                                        <td class="text-right">{{ format_currency($recharge['amount_total'], true) }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="6">{{ lang_trans('text_no_results') }}</td>
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
