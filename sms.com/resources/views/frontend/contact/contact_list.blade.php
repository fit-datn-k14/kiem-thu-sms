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
                                <div class="menu-button-container" style="display:inline-block;margin-right: 30px">
                                    <button class="mdc-button mdc-menu-button mdc-button--outlined outlined-button--info mdc-button--dense">
                                        <i class="material-icons mdc-button__icon">attach_file</i> {{ lang_trans('button_import_excel') }}
                                    </button>
                                    <div class="mdc-menu mdc-menu-surface" tabindex="-1" id="demo-menu">
                                        <ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical">
                                            <li class="mdc-list-item" role="menuitem">
                                                <h6 class="item-subject font-weight-normal" onclick="excelImport()">{{ lang_trans('button_import_file_excel') }}</h6>
                                            </li>
                                            <li class="mdc-list-item" role="menuitem">
                                                <h6 class="item-subject font-weight-normal" onclick="excelImport()">{{ lang_trans('button_download_file_excel') }}</h6>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <a href="{{ $add }}" class="mdc-button mdc-button--outlined mdc-button--dense"><i class="material-icons mdc-button__icon">add</i> {{ lang_trans('button_add') }}
                                </a>
                                <a onclick="confirm('{{ lang_trans('text_confirm') }}') ? $('#form').submit() : false;" class="mdc-button mdc-button--outlined outlined-button--secondary mdc-button--dense"><i class="material-icons mdc-button__icon">delete</i> {{ lang_trans('button_delete') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="box__filter">
                        <div class="mdc-layout-grid__inner form-group">
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2">
                                <div class="mdc-text-field mdc-text-field--outlined">
                                    <input type="text" class="mdc-text-field__input" name="filter_full_name" value="{{ $filter_full_name }}">
                                    <div class="mdc-notched-outline">
                                        <div class="mdc-notched-outline__leading"></div>
                                        <div class="mdc-notched-outline__notch">
                                            <label class="mdc-floating-label">{{ lang_trans('entry_full_name') }}</label>
                                        </div>
                                        <div class="mdc-notched-outline__trailing"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2">
                                <div class="mdc-text-field mdc-text-field--outlined">
                                    <input type="text" class="mdc-text-field__input" name="filter_phone" value="{{ $filter_phone }}">
                                    <div class="mdc-notched-outline">
                                        <div class="mdc-notched-outline__leading"></div>
                                        <div class="mdc-notched-outline__notch">
                                            <label class="mdc-floating-label">{{ lang_trans('entry_phone') }}</label>
                                        </div>
                                        <div class="mdc-notched-outline__trailing"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2">
                                <div class="mdc-text-field mdc-text-field--outlined">
                                    <input type="text" class="mdc-text-field__input" name="filter_address" value="{{ $filter_address }}">
                                    <div class="mdc-notched-outline">
                                        <div class="mdc-notched-outline__leading"></div>
                                        <div class="mdc-notched-outline__notch">
                                            <label for="input-address" class="mdc-floating-label">{{ lang_trans('entry_address') }}</label>
                                        </div>
                                        <div class="mdc-notched-outline__trailing"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2">
                                <div class="mdc-select demo-width-class" data-mdc-auto-init="MDCSelect">
                                    <input type="hidden" name="filter_status" value="{{ $filter_status }}">
                                    <i class="mdc-select__dropdown-icon"></i>
                                    <div class="mdc-select__selected-text"></div>
                                    <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                                        <ul class="mdc-list">
                                            <li class="mdc-list-item mdc-list-item--selected" data-value=""></li>
                                            @if($filter_status == '1')
                                                <li class="mdc-list-item" data-value="1" aria-selected="true">{{ lang_trans('text_enabled') }}</li>
                                            @else
                                                <li class="mdc-list-item" data-value="1">{{ lang_trans('text_enabled') }}</li>
                                            @endif
                                            @if($filter_status == '0')
                                                <li class="mdc-list-item" data-value="0" aria-selected="true">{{ lang_trans('text_disabled') }}</li>
                                            @else
                                                <li class="mdc-list-item" data-value="0">{{ lang_trans('text_disabled') }}</li>
                                            @endif
                                        </ul>
                                    </div>
                                    <span class="mdc-floating-label">{{ lang_trans('entry_status') }}</span>
                                    <div class="mdc-line-ripple"></div>
                                </div>
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2">
                                <button type="button" id="button-filter" class="mdc-button mdc-button--unelevated filled-button--secondary">
                                    <i class="material-icons mdc-button__icon">flash_on</i> {{ lang_trans('button_filter') }}
                                </button>
                            </div>
                        </div>
                    </div>
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
                                    <th style="width: 1px;" class="text-left">{{ lang_trans('column_order_number') }}</th>
                                    <th class="text-left">
                                        @if($sort == 'full_name')
                                            <a href="{{ $sort_full_name }}" class="table-column-order">{{ lang_trans('column_full_name') }} <i class="material-icons">{{ $order == 'asc' ? 'arrow_drop_up' : 'arrow_drop_down' }}</i></a>
                                        @else
                                            <a href="{{ $sort_full_name }}">{{ lang_trans('column_full_name') }}</a>
                                        @endif
                                    </th>
                                    <th class="text-left">
                                        @if($sort == 'phone')
                                            <a href="{{ $sort_phone }}" class="table-column-order">{{ lang_trans('entry_phone') }} <i class="material-icons">{{ $order == 'asc' ? 'arrow_drop_up' : 'arrow_drop_down' }}</i></a>
                                        @else
                                            <a href="{{ $sort_phone }}">{{ lang_trans('entry_phone') }}</a>
                                        @endif
                                    </th>
                                    <th style="max-width: 250px" class="text-left">{{ lang_trans('entry_address') }}</th>
                                    <th style="max-width: 250px" class="text-left">{{ lang_trans('entry_note') }}</th>
                                    <th class="text-center">{{ lang_trans('column_status') }}</th>
                                    <th style="width: 66px" class="text-right">{{ lang_trans('column_action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($contacts)
                                    @foreach($contacts as $contact)
                                        <tr>
                                            <td class="text-left">
                                                <div class="mdc-form-field">
                                                    <div class="mdc-checkbox">
                                                        @if(in_array($contact['id'], $selected))
                                                            <input type="checkbox" name="selected[]" value="{{ $contact['id'] }}" checked class="mdc-checkbox__native-control"/>
                                                        @else
                                                            <input type="checkbox" name="selected[]" value="{{ $contact['id'] }}" class="mdc-checkbox__native-control"/>
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
                                            <td style="width: 1px;" class="text-left">{{ $contact['order_number'] }}</td>
                                            <td class="text-left">{{ $contact['full_name'] }}</td>
                                            <td class="text-left">{{ $contact['phone'] }}</td>
                                            <td style="max-width: 250px" class="text-left">{{ $contact['address'] }}</td>
                                            <td style="max-width: 250px" class="text-left">{{ $contact['note'] }}</td>
                                            <td class="text-center">
                                                <a class="{{ $contact['status'] ? 'enabled' : 'disabled' }}" data-ajax="status" _status="{{ $contact['id'] }},{{ $contact['status'] }}" data-toggle="tooltip" title="{{ $contact['status'] ? lang_trans('text_enabled') : lang_trans('text_disabled_note') }}">
                                                    @if($contact['status'])
                                                        <i class="material-icons">check_circle</i>
                                                    @else
                                                        <i class="material-icons">not_interested</i>
                                                    @endif
                                                </a>
                                            </td>
                                            <td style="width: 66px" class="text-right">
                                                <div class="table-actions">
                                                    <div class="action">
                                                        <a href="{{ $contact['edit'] }}" class="mdc-button mdc-button--outlined icon-button mdc-button--dense" data-toggle="tooltip" title="{{ lang_trans('button_edit') }}"><i class="material-icons mdc-button__icon">colorize</i></a>
                                                        <a onclick="confirm('{{ lang_trans('text_confirm') }}') ? location = '{{ $contact['delete'] }}' : false;" href="javascript:void(0)" class="mdc-button mdc-button--outlined icon-button mdc-button--dense outlined-button--secondary" data-toggle="tooltip" title="{{ lang_trans('button_delete') }}"><i class="material-icons mdc-button__icon">clear</i></a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="8">{{ lang_trans('text_no_results') }}</td>
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
    {{--Modal alert--}}
    <div class="mdc-dialog" id="modal__sms__alert">
        <div class="mdc-dialog__container">
            <div class="mdc-dialog__surface"
                 role="alertdialog"
                 aria-modal="true"
                 aria-labelledby="my-dialog-title"
                 aria-describedby="my-dialog-content">
                <div class="mdc-dialog__content" id="my-dialog-content__alert"></div>
                <div class="mdc-dialog__actions">
                    <button type="button" class="mdc-button mdc-button--unelevated mdc-button--dense" data-mdc-dialog-action="cancel">
                        <div class="mdc-button__ripple"></div>
                        <span class="mdc-button__label">OK</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="mdc-dialog__scrim"></div>
    </div>
@endsection
@push('script_footer')
    <script type="text/javascript">
        changeAjaxStatus('{{ site_url('contact/contact/ajax-status') }}', 'contact_id');

        $('#button-filter').on('click', function () {
            var url = '';
            var filter_full_name = $('input[name=\'filter_full_name\']').val();
            if (filter_full_name) {
                url += '&filter_full_name=' + encodeURIComponent(filter_full_name);
            }
            var filter_status = $('input[name=\'filter_status\']').val();
            if (filter_status !== '') {
                url += '&filter_status=' + encodeURIComponent(filter_status);
            }
            var filter_address = $('input[name=\'filter_address\']').val();
            if (filter_address) {
                url += '&filter_address=' + encodeURIComponent(filter_address);
            }
            var filter_phone = $('input[name=\'filter_phone\']').val();
            if (filter_phone) {
                url += '&filter_phone=' + encodeURIComponent(filter_phone);
            }
            if  (url !== ''){
                location = '{{ site_url('contact/contact') }}?' + url;
            } else {
                location = '{{ site_url('contact/contact') }}';
            }
        });
    </script>
    <script>
        var dialogElAlert = document.querySelector('#modal__sms__alert');
        var dialogAlert = new mdc.dialog.MDCDialog(dialogElAlert);

        function excelImport() {
            $('#my-dialog-content__alert').text('Tính năng đang được cập nhật!');
            dialogAlert.open();
        }
    </script>
@endpush
