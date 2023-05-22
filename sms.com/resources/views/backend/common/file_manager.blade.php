<div id="filemanager" class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header" style="padding: 8px 15px;">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{{ lang_trans('heading_title') }}</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-8">
                    <a href="{{ $parent }}" id="button-parent" class="btn btn-primary"><i class="fa fa-level-up"></i> {{ lang_trans('button_parent') }}
                    </a>
                    <a href="{{ $refresh }}" id="button-refresh" class="btn btn-primary"><i class="fa fa-refresh"></i> {{ lang_trans('button_refresh') }}
                    </a>
                    <button type="button" id="button-folder" class="btn btn-primary"><i class="fa fa-folder"></i> {{ lang_trans('button_folder') }}</button>
                    <button type="button" id="button-upload" class="btn btn-primary"><i class="fa fa-upload"></i> {{ lang_trans('button_upload') }}</button>
                    <button type="button" style="margin-left: 10px;" id="button-delete" class="btn btn-danger"><i class="fa fa-trash-o"></i> {{ lang_trans('button_delete') }}</button>
                    <span class="pull-right">
                    <input type="checkbox" class="minimal check" id="check-all-image"> <label for="check-all-image">{{ lang_trans('text_select_all') }}</label></span>
                </div>
                <div class="col-sm-4">
                    <div class="input-group hidden">
                        <input type="text" style="height: 27px;width: 184px;" name="search" value="{{ $filter_name }}" placeholder="{{ lang_trans('entry_search') }}" class="form-control">
                        <span class="input-group-btn">
            <button type="button" id="button-search" class="btn btn-primary"><i class="fa fa-search"></i> {{ lang_trans('button_search') }}</button>
            </span></div>
                </div>
            </div>
            <hr>
            <div class="main-content-files">
                <div class="row">
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <div class="column-left">
                            <ul>
                                <li @unless($directory)class="active"@endunless>
                                    <input type="checkbox" class="pull-left" readonly disabled />
                                    <a href="{{ $root_directory_path }}" class="directory" style="color: #2a3f54 ">
                                        {{ 'DATA' }}
                                    </a>
                                    @if($all_directories)
                                    <ul>
                                        @foreach($all_directories as $directory_1)
                                        <li @if($directory_1['dir_active'] == $directory)class="active" @endif>
                                            @if($directory_1['sub'])
                                            <input type="checkbox" class="pull-left" name="path[]" value="{{ $directory_1['path'] }}" />
                                            <a href="{{ $directory_1['href'] }}" class="directory" style="color: #2a3f54 ">
                                                {{ $directory_1['name'] }}
                                            </a>
                                            <ul>
                                                @foreach($directory_1['sub'] as $directory_2)
                                                <li @if($directory_2['dir_active'] == $directory)class="active" @endif>
                                                    @if($directory_2['sub'])
                                                    <input type="checkbox" class="pull-left" name="path[]" value="{{ $directory_2['path'] }}" />
                                                    <a href="{{ $directory_2['href'] }}" class="directory" style="color: #2a3f54 ">
                                                        {{ $directory_2['name'] }}
                                                    </a>
                                                    <ul>
                                                        @foreach($directory_2['sub'] as $directory_3)
                                                        <li @if($directory_3['dir_active'] == $directory)class="active" @endif>
                                                            @if($directory_3['sub'])
                                                            <input type="checkbox" class="pull-left" name="path[]" value="{{ $directory_3['path'] }}" />
                                                            <a href="{{ $directory_3['href'] }}" class="directory" style="color: #2a3f54 ">
                                                                {{ $directory_3['name'] }}
                                                            </a>
                                                            <ul>
                                                                @foreach($directory_3['sub'] as $directory_4)
                                                                <li @if($directory_4['dir_active'] == $directory)class="active" @endif>
                                                                    @if($directory_4['sub'])
                                                                    <input type="checkbox" class="pull-left" name="path[]" value="{{ $directory_4['path'] }}" />
                                                                    <a href="{{ $directory_4['href'] }}" class="directory" style="color: #2a3f54 ">
                                                                        {{ $directory_4['name'] }}
                                                                    </a>
                                                                    <ul>
                                                                        @foreach($directory_4['sub'] as $directory_5)
                                                                        <li @if($directory_5['dir_active'] == $directory)class="active" @endif>
                                                                            @if($directory_5['sub'])
                                                                            <input type="checkbox" class="pull-left" name="path[]" value="{{ $directory_5['path'] }}" />
                                                                            <a href="{{ $directory_5['href'] }}" class="directory" style="color: #2a3f54 ">
                                                                                {{ $directory_5['name'] }}
                                                                            </a>
                                                                            <ul>
                                                                                @foreach($directory_5['sub'] as $directory_6)
                                                                                <li @if($directory_6['dir_active'] == $directory)class="active" @endif>
                                                                                    <input type="checkbox" class="pull-left" name="path[]" value="{{ $directory_6['path'] }}" />
                                                                                    <a href="{{ $directory_6['href'] }}" class="directory" style="color: #2a3f54 ">
                                                                                        {{ $directory_6['name'] }}
                                                                                    </a>
                                                                                </li>
                                                                                @endforeach
                                                                            </ul>
                                                                            @else
                                                                            <input type="checkbox" class="pull-left" name="path[]" value="{{ $directory_5['path'] }}" />
                                                                            <a href="{{ $directory_5['href'] }}" class="directory" style="color: #2a3f54 ">
                                                                                {{ $directory_5['name'] }}
                                                                            </a>
                                                                            @endif
                                                                        </li>
                                                                        @endforeach
                                                                    </ul>
                                                                    @else
                                                                    <input type="checkbox" class="pull-left" name="path[]" value="{{ $directory_4['path'] }}" />
                                                                    <a href="{{ $directory_4['href'] }}" class="directory" style="color: #2a3f54 ">
                                                                        {{ $directory_4['name'] }}
                                                                    </a>
                                                                    @endif
                                                                </li>
                                                                @endforeach
                                                            </ul>
                                                            @else
                                                            <input type="checkbox" class="pull-left" name="path[]" value="{{ $directory_3['path'] }}" />
                                                            <a href="{{ $directory_3['href'] }}" class="directory" style="color: #2a3f54 ">
                                                                {{ $directory_3['name'] }}
                                                            </a>
                                                            @endif
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                    @else
                                                    <input type="checkbox" class="pull-left" name="path[]" value="{{ $directory_2['path'] }}" />
                                                    <a href="{{ $directory_2['href'] }}" class="directory" style="color: #2a3f54 ">
                                                        {{ $directory_2['name'] }}
                                                    </a>
                                                    @endif
                                                </li>
                                                @endforeach
                                            </ul>
                                            @else
                                            <input type="checkbox" class="pull-left" name="path[]" value="{{ $directory_1['path'] }}" />
                                            <a href="{{ $directory_1['href'] }}" class="directory" style="color: #2a3f54 ">
                                                {{ $directory_1['name'] }}
                                            </a>
                                            @endif
                                        </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                        <div id="box-image-manager">
                            @if($images_file)
                                @foreach($images_file as $images)
                                <div class="row">
                                    @foreach($images as $image)
                                    <div class="col-sm-2 col-xs-3 text-center">
                                        <div class="text-center">
                                            <a href="{{ $image['href'] }}" class="thumbnail">
                                                <label class="extension">{{ $image['extension'] }}</label>
                                                <img src="{{ $image['thumb'] }}" alt="{{ $image['name'] }}" title="{{ $image['name'] }}"/>
                                            </a>
                                        </div>
                                        <label>
                                            <input class="pull-left" type="checkbox" name="path[]" value="{{ $image['path'] }}" />
                                            {{ $image['name'] }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                <br />
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">{!! $pagination !!}</div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#check-all-image').click(function () {
            if($(this).prop('checked') == true){
                $('#box-image-manager').find('input[type="checkbox"]').prop('checked',true);
                $('#box-image-manager').parent().parent().find('a.thumbnail').css({'border':'1px solid #f56b6b','background':'#f56b6b'});
                $('#box-image-manager').find('input[type="checkbox"]').parent().addClass('file-active');
            }else{
                $('#box-image-manager').find('input[type="checkbox"]').prop('checked',false);
                $('#box-image-manager').parent().parent().find('a.thumbnail').css({'border':'1px solid #cbdae8','background':'#fff'});
                $('#box-image-manager').find('input[type="checkbox"]').parent().removeClass('file-active');
            }
        });
    });
</script>
<script type="text/javascript"><!--
    @if($target)
    $('a.thumbnail').on('click', function (e) {
        e.preventDefault();

        @if($thumb)
        $('#{{ $thumb }}').find('img').attr('src', $(this).find('img').attr('src'));
        @endif

        $('#{{ $target }}').val($(this).parent().parent().find('input').val());
        $('#modal-image').modal('hide');
    });
    @endif

    $('a.directory').on('click', function (e) {
        e.preventDefault();

        $('#modal-image').load($(this).attr('href'));
    });

    $('.pagination a').on('click', function (e) {
        e.preventDefault();

        $('#modal-image').load($(this).attr('href'));
    });

    $('#button-parent').on('click', function (e) {
        e.preventDefault();

        $('#modal-image').load($(this).attr('href'));
    });

    $('#button-refresh').on('click', function (e) {
        e.preventDefault();

        $('#modal-image').load($(this).attr('href'));
    });

    $('input[name=\'search\']').on('keydown', function (e) {
        if (e.which == 13) {
            $('#button-search').trigger('click');
        }
    });

    $('#button-search').on('click', function (e) {
        var url = '{{ site_url('common/file-manager') . '?' . 'directory=' . $directory }}';

        var filterName = $('input[name=\'search\']').val();
        if (filterName) {
            url += '&filter_name=' + encodeURIComponent(filterName);
        }

        @if($thumb)
        url += '&thumb=' + '{{ $thumb }}';
        @endif

        @if($target)
        url += '&target=' + '{{ $target }}';
        @endif

        @if($ckedialog)
        url += '&ckedialog=' + '{{ $ckedialog }}';
        @endif

        $('#modal-image').load(url);
    });
    //--></script>
<script type="text/javascript"><!--
    $('#button-upload').on('click', function () {
        $('#form-upload').remove();

        $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file[]" value="" multiple="multiple" /></form>');

        $('#form-upload input[name=\'file[]\']').trigger('click');

        if (typeof timer != 'undefined') {
            clearInterval(timer);
        }

        timer = setInterval(function () {
            if ($('#form-upload input[name=\'file[]\']').val() != '') {
                clearInterval(timer);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ site_url('common/file-manager/upload') . '?' . 'directory=' . $directory }}',
                    type: 'post',
                    dataType: 'json',
                    data: new FormData($('#form-upload')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#button-upload i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                        $('#button-upload').prop('disabled', true);
                    },
                    complete: function () {
                        $('#button-upload i').replaceWith('<i class="fa fa-upload"></i>');
                        $('#button-upload').prop('disabled', false);
                    },
                    success: function (json) {
                        if (json['error']) {
                            alert(json['error']);
                        }

                        if (json['success']) {
                            alert(json['success']);

                            $('#button-refresh').trigger('click');
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }, 500);
    });

    $('#button-folder').popover({
        html: true,
        placement: 'bottom',
        trigger: 'click',
        title: '{{ lang_trans('entry_folder') }}',
        content: function () {
            html = '<div class="input-group">';
            html += '  <input type="text" style="height: 27px;" name="folder" value="" placeholder="{{ lang_trans('entry_folder') }}" class="form-control">';
            html += '  <span class="input-group-btn"><button type="button" title="{{ lang_trans('button_folder') }}" id="button-create" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></span>';
            html += '</div>';

            return html;
        }
    });

    $('#button-folder').on('shown.bs.popover', function () {
        $('#button-create').on('click', function () {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ site_url('common/file-manager/folder') . '?' . 'directory=' . $directory }}',
                type: 'post',
                dataType: 'json',
                data: 'folder=' + encodeURIComponent($('input[name=\'folder\']').val()),
                beforeSend: function () {
                    $('#button-create').prop('disabled', true);
                },
                complete: function () {
                    $('#button-create').prop('disabled', false);
                },
                success: function (json) {
                    if (json['error']) {
                        alert(json['error']);
                    }

                    if (json['success']) {
                        alert(json['success']);

                        $('#button-refresh').trigger('click');
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    });

    $('#modal-image #button-delete').on('click', function (e) {
        if (confirm('{{ lang_trans('text_confirm') }}')) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ site_url('common/file-manager/delete') }}',
                type: 'post',
                dataType: 'json',
                data: $('input[name^=\'path\']:checked'),
                beforeSend: function () {
                    $('#button-delete').prop('disabled', true);
                },
                complete: function () {
                    $('#button-delete').prop('disabled', false);
                },
                success: function (json) {
                    if (json['error']) {
                        alert(json['error']);
                    }

                    if (json['success']) {
                        alert(json['success']);

                        $('#button-refresh').trigger('click');
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    });

    @if($ckedialog)
    $('a.thumbnail').on('click', function (e) {
        e.preventDefault();
        dialog = CKEDITOR.dialog.getCurrent();
        var targetElement = '{{ $ckedialog }}' || null;
        var target = targetElement.split(':');
        dialog.setValueOf(target[0], target[1], this.getAttribute('href'));
        $('#modal-image').modal('hide');
    });
    @endif
    //--></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#filemanager label').delegate('input', 'click', function (){
            if($(this).prop("checked") == true){
                $(this).parent().parent().find('a.thumbnail').css({'border':'1px solid #f56b6b','background':'#f56b6b'});
                $(this).parent().parent().find('a.directory').find('i').css('color','#f56b6b');
                $(this).css('background', '#cbdae8');
                $(this).parent().addClass('file-active');
            }else if($(this).prop("checked") == false){
                $(this).parent().parent().find('a.thumbnail').css({'border':'1px solid #cbdae8','background':'#fff'});
                $(this).parent().parent().find('a.directory').find('i').css('color','#2a3f54');
                $(this).css('background', '#fff');
                $(this).parent().removeClass('file-active');
            }
        });
    });
    if (typeof(Storage) !== "undefined") {
        localStorage.directory = '{{ $directory }}';
    }
</script>
