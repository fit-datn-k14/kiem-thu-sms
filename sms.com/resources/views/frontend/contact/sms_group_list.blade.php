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
                                <a href="javascript:void(0)" id="button_open_send_sms" class="mdc-button mdc-button--unelevated mdc-button--dense"><i class="material-icons mdc-button__icon">chat</i> {{ lang_trans('button_send_sms') }}</a>
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
                                                            <input type="checkbox" name="selected[]" value="{{ $contact['id'] }}" checked class="mdc-checkbox__native-control mdc-checkbox__contact-id"/>
                                                        @else
                                                            <input type="checkbox" name="selected[]" value="{{ $contact['id'] }}" class="mdc-checkbox__native-control mdc-checkbox__contact-id"/>
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
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="5">{{ lang_trans('text_no_results') }}</td>
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

    {{--Modal sms--}}
    <div class="mdc-dialog @if($sms_config['total_sms_remain']) mdc-dialog__sms__typing @endif" id="modal__sms__typing">
        <div class="mdc-dialog__container">
            <div class="mdc-dialog__surface"
                 role="alertdialog"
                 aria-modal="true"
                 aria-labelledby="my-dialog-title"
                 aria-describedby="my-dialog-content">
                <h2 class="mdc-dialog__title" id="my-dialog-title">Gửi SMS</h2>
                <div class="mdc-dialog__content" id="my-dialog-content">
                    @if($sms_config['total_sms_remain'])
                        <div class="sms_sending_note_wrap">
                            <div class="sms_total_remain">Số tin nhắn còn lại: <span>{{ format_currency($sms_config['total_sms_remain']) }}</span> (SMS)</div>
                            <div id="sms_sending_note"></div>
                        </div>
                        <div id="sms_sending_note_loading"></div>
                        <div class="mdc-layout-grid__inner">
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4">
                                <div class="message-input">
                                    <label class="control-label">{{ lang_trans('entry_content') }}</label>
                                    <div class="control-content">
                                        <textarea maxlength="{{ 459 - $sms_config['sms_prefix_length'] }}" name="sms_txt_content"  class="form-control" id="sms_txt_content" cols="30" rows="12"></textarea>
                                        <div class="sms-typing-content__action">
                                            <span id="c_msg"></span>
                                            <a href="javascript:void(0)" class="action" onclick="reset()">Xóa nội dung</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-8">
                                <div class="mdc-layout-grid__inner" style="width: 100%;">
                                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4">
                                        <div class="message-preview">
                                            <label class="control-label">{{ lang_trans('entry_content_1') }}</label>
                                            <div class="control-content">
                                                <div id="sms_txt_content_msg1"></div>
                                                <span id="c_msg1"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4">
                                        <div class="message-preview">
                                            <label class="control-label">{{ lang_trans('entry_content_2') }}</label>
                                            <div class="control-content">
                                                <div id="sms_txt_content_msg2"></div>
                                                <span id="c_msg2"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4">
                                        <div class="message-preview">
                                            <label for="entry_class" class="control-label">{{ lang_trans('entry_content_3') }}</label>
                                            <div class="control-content">
                                                <div id="sms_txt_content_msg3"></div>
                                                <span id="c_msg3"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="sms_sending_note_wrap">
                            <div class="sms_note">Số tin nhắn còn lại: <span>{{ $sms_config['total_sms_remain'] }}</span> (SMS)</div>
                            <div class="sms_note">Số dư tài khoản hiện tại: <span>{{ format_currency($sms_config['customer_money']) }}</span> (đ)</div>
                            <div class="sms_note">Trong tài khoản không đủ tiền để gửi tin nhắn</div>
                            <div class="sms_note">Vui lòng nạp thêm tiền vào tài khoản</div>
                        </div>
                    @endif
                </div>
                <div class="mdc-dialog__actions">
                    @if($sms_config['total_sms_remain'])
                        <a href="javascript:void(0)" id="do__send_message" class="mdc-button mdc-button--unelevated mdc-button--dense"><i class="material-icons mdc-button__icon">trending_up</i> <span>{{ lang_trans('button_send') }}</span></a>
                    @endif
                    <a href="javascript:void(0)" id="do__close_modal" class="mdc-button mdc-button--unelevated mdc-button--dense filled-button--secondary close__modal" data-mdc-dialog-action="close"><i class="material-icons mdc-button__icon">clear</i> <span>Đóng</span></a>
                </div>
            </div>
        </div>
        <div class="mdc-dialog__scrim"></div>
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
    <script>
        $('#button-filter').on('click', function () {
            var url = '';
            var filter_full_name = $('input[name=\'filter_full_name\']').val();
            if (filter_full_name) {
                url += '&filter_full_name=' + encodeURIComponent(filter_full_name);
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
                location = '{{ site_url('contact/sms-group') }}?' + url;
            } else {
                location = '{{ site_url('contact/sms-group') }}';
            }
        });
    </script>
    @if($sms_config['total_sms_remain'])
        <script>
            var msgId = 'sms_txt_content';
            var msg1 = 'sms_txt_content_msg1';
            var msg2 = 'sms_txt_content_msg2';
            var msg3 = 'sms_txt_content_msg3';

            $(document).ready(function () {
                docReady();
            });
            function docReady() {
                syncMessage();
                $("#" + msgId).bind("paste", function () {
                    setTimeout(function () { syncMessage(); }, 100);
                });
                $("#" + msgId).keydown(function () {
                    syncMessage();
                });
                $("#" + msgId).keyup(function () {
                    syncMessage();
                });
                $("#" + msgId).mousedown(function () {
                    syncMessage();
                });
            }
            function syncMessage() {
                var text = $("#" + msgId).val();
                $("#c_msg").html(text.length + {{ $sms_config['sms_prefix_length'] }});
                var msg1Content;
                var msg2Content;
                var msg3Content;

                var smsPrefix = '';
                @if ($sms_config['sms_prefix_length'])
                    smsPrefix = filterViToEn('{{ $sms_config['customer_sms_prefix'] }}') + ' ';
                @endif

                $("#" + msg1).html(smsPrefix + filterViToEn(text));
                $("#" + msg2).html('');
                $("#" + msg3).html('');

                msg1Content = text;
                msg2Content = "";
                msg3Content = "";

                if ((text.length + {{ $sms_config['sms_prefix_length'] }}) > 160 && (text.length + {{ $sms_config['sms_prefix_length'] }}) <= 306) {
                    msg1Content = text.substring(0, 160 - {{ $sms_config['sms_prefix_length'] }});
                    msg2Content = text.substring(160 - {{ $sms_config['sms_prefix_length'] }}, 306 - {{ $sms_config['sms_prefix_length'] }});
                    msg3Content = "";
                    $("#" + msg1).html(smsPrefix + filterViToEn(msg1Content));
                    $("#" + msg2).html(filterViToEn(msg2Content));
                    $("#" + msg3).html(filterViToEn(msg3Content));
                }
                if ((text.length + {{ $sms_config['sms_prefix_length'] }})> 306 && (text.length + {{ $sms_config['sms_prefix_length'] }}) <= 459) {
                    msg1Content = text.substring(0, 160 - {{ $sms_config['sms_prefix_length'] }});
                    msg2Content = text.substring(160 - {{ $sms_config['sms_prefix_length'] }}, 306 - {{ $sms_config['sms_prefix_length'] }});
                    msg3Content = text.substring(306 - {{ $sms_config['sms_prefix_length'] }}, 459 - {{ $sms_config['sms_prefix_length'] }});
                    $("#" + msg1).html(smsPrefix + filterViToEn(msg1Content));
                    $("#" + msg2).html(filterViToEn(msg2Content));
                    $("#" + msg3).html(filterViToEn(msg3Content));
                }
                if ((text.length + {{ $sms_config['sms_prefix_length'] }}) > 459) {
                    msg1Content = text.substring(0, 160 - {{ $sms_config['sms_prefix_length'] }});
                    msg2Content = text.substring(160 - {{ $sms_config['sms_prefix_length'] }}, 306 - {{ $sms_config['sms_prefix_length'] }});
                    msg3Content = text.substring(306 - {{ $sms_config['sms_prefix_length'] }}, 459 - {{ $sms_config['sms_prefix_length'] }});
                    $("#" + msg1).html(smsPrefix + filterViToEn(msg1Content));
                    $("#" + msg2).html(filterViToEn(msg2Content));
                    $("#" + msg3).html(filterViToEn(msg3Content) + "...");
                }
                var msg1Remain = msg1Content.length + {{ $sms_config['sms_prefix_length'] }};
                var msg2Remain = msg2Content.length;
                var msg3Remain = msg3Content.length;
                $("#c_msg1").html(msg1Remain);
                $("#c_msg2").html(msg2Remain);
                $("#c_msg3").html(msg3Remain);
            }
            function reset() {
                $("#" + msgId).val('');
                $("#" + msg1).html('');
                $("#" + msg2).html('');
                $("#" + msg3).html('');
                $("#c_msg").html('0');
                $("#c_msg1").html('0');
                $("#c_msg2").html('0');
                $("#c_msg3").html('0');
            }
        </script>
    @endif
    <script>
        var dialogElTyping = document.querySelector('#modal__sms__typing');
        var dialogTyping = new mdc.dialog.MDCDialog(dialogElTyping);
        dialogTyping.scrimClickAction = ""; // disable click outside
        dialogTyping.escapeKeyAction = ""; // for escape

        var dialogElAlert = document.querySelector('#modal__sms__alert');
        var dialogAlert = new mdc.dialog.MDCDialog(dialogElAlert);

        $(document).ready(function () {
            var filterContact;
            var filterContactJoin;

            $("#sms_sending_note").empty();
            $("#button_open_send_sms").on('click', function () {
                filterContact = [];
                var inputElements = document.getElementsByClassName('mdc-checkbox__contact-id');
                for (var i=0; i < inputElements.length; ++i){
                    if(inputElements[i].checked){
                        filterContact.push(inputElements[i].value);
                    }
                }

                if (filterContact.length !== 0){
                    filterContactJoin = filterContact.join(',');
                    $("#sms_sending_note").html("Nội dung SMS sẽ được gửi đến <span>"+ filterContact.length +"</span> số điện thoại đã chọn");
                    $('#sms_sending_note_loading').empty();
                    dialogTyping.open();
                } else {
                    $('#my-dialog-content__alert').text('Vui lòng chọn liên lạc gửi SMS!');
                    dialogAlert.open();
                    return false;
                }
            });

            $('#do__send_message').on('click', (function () {
                $('#sms_sending_note_loading').empty();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ site_url('contact/sms-group/send-sms') }}',
                    type: 'post',
                    data: 'content=' + encodeURIComponent($('textarea[name="sms_txt_content"]').val()) + '&contact=' + encodeURIComponent(filterContactJoin),
                    dataType: 'json',
                    beforeSend: function () {
                        $('#sms_sending_note_loading').html('<h3 style="font-size: 18px;" class="text-primary text-center text-bold"><span class="fa fa-spinner fa-spin"></span> {{ lang_trans('text_progress') }}</h3>');
                        $('#do__send_message').find('span').text('Loading...');
                        $('#do__close_modal').find('span').text('Loading...');
                    },
                    success: function (data){
                        if(data['error']){
                            $("#sms_sending_note_loading").html("<div class='alert alert-danger'>"+ data['error'] +"</div>");
                        }
                        if(data['data']){
                            reset();
                            $("#sms_sending_note_loading").html("<div class='alert alert-success'>"+ data['data'] +"</div>");
                        }
                    },
                    complete: function () {
                        $('#do__send_message').find('span').text('Gửi');
                        $('#do__close_modal').find('span').text('Đóng');
                    }
                });
            }));

            $('.close__modal').on('click', function () {
                filterContact = [];
                reset();
                dialogTyping.close();
            });
        });
    </script>
@endpush
