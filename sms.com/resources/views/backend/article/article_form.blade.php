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
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-general" data-toggle="tab">{{ lang_trans('tab_general') }}</a></li>
                        <li><a href="#tab-data" data-toggle="tab">{{ lang_trans('tab_data') }}</a></li>
                        <li><a href="#tab-seo" data-toggle="tab">{{ lang_trans('tab_seo') }}</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-general">
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-name">{{ lang_trans('entry_name') }}</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" value="{{ $name }}" placeholder="{{ lang_trans('entry_name') }}" id="input-name" class="form-control"/>
                                    @if($errors->has('name'))
                                        <p class="text-danger">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-description">{{ lang_trans('entry_description') }}</label>
                                <div class="col-sm-10">
                                    <textarea name="description" placeholder="{{ lang_trans('entry_description') }}" id="input-description" class="form-control">{{ $description }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-tag">{{ lang_trans('entry_tag') }}</label>
                                <div class="col-sm-10">
                                    <input type="text" name="tag" value="{{ $tag }}" placeholder="{{ lang_trans('entry_tag') }}" id="input-tag" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-data">
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
                                <label class="col-sm-2 control-label" for="input-category"><span data-toggle="tooltip" title="{{ lang_trans('help_category') }}">{{ lang_trans('entry_category') }}</span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="category" value="" placeholder="{{ lang_trans('entry_category') }}" id="input-category" class="form-control"/>
                                    <div id="article-category" class="well well-sm" style="height: 150px; overflow: auto;">
                                        @foreach($article_categories as $article_category)
                                            <div id="article-category{{ $article_category['id'] }}">
                                                <i class="fa fa-minus-circle"></i> {{ $article_category['name'] }}
                                                <input type="hidden" name="article_category[]" value="{{ $article_category['id'] }}"/>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-author">{{ lang_trans('entry_author') }}</label>
                                <div class="col-sm-10">
                                    <input type="text" name="author" value="{{ $author }}" placeholder="{{ lang_trans('entry_author') }}" id="input-author" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-sort-order">{{ lang_trans('entry_sort_order') }}</label>
                                <div class="col-sm-10">
                                    <input type="text" name="sort_order" value="{{ $sort_order }}" placeholder="{{ lang_trans('entry_sort_order') }}" id="input-sort-order" class="form-control"/>
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
                                <label class="col-sm-2 control-label" for="input-featured">{{ lang_trans('entry_featured') }}</label>
                                <div class="col-sm-10">
                                    @if ($featured)
                                        <label class="radio-inline">
                                            <input type="radio" name="featured" value="1" checked="checked"/> {{ lang_trans('text_enabled') }}
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="featured" value="0"/> {{ lang_trans('text_disabled') }}
                                        </label>
                                    @else
                                        <label class="radio-inline">
                                            <input type="radio" name="featured" value="1"/> {{ lang_trans('text_enabled') }}
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="featured" value="0" checked="checked"/> {{ lang_trans('text_disabled') }}
                                        </label>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-seo">
                            <div class="alert alert-info"><i class="fa fa-info-circle"></i> {{ lang_trans('text_keyword') }}</div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-keyword">{{ lang_trans('entry_keyword') }}</label>
                                <div class="col-sm-10">
                                    <input type="text" name="url_alias" value="{{ $url_alias }}" placeholder="{{ lang_trans('entry_keyword') }}" class="form-control"/>
                                    @if ($errors->has('url_alias'))
                                        <div class="text-danger">{{ $errors->first('url_alias') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-meta-title">{{ lang_trans('entry_meta_title') }}</label>
                                <div class="col-sm-10">
                                    <input type="text" name="meta_title" value="{{ $meta_title }}" placeholder="{{ lang_trans('entry_meta_title') }}" id="input-meta-title" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-meta-description">{{ lang_trans('entry_meta_description') }}</label>
                                <div class="col-sm-10">
                                    <textarea name="meta_description" rows="5" placeholder="{{ lang_trans('entry_meta_description') }}" id="input-meta-description" class="form-control">{{ $meta_description }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-meta-keyword">{{ lang_trans('entry_meta_keyword') }}</label>
                                <div class="col-sm-10">
                                    <textarea name="meta_keyword" rows="5" placeholder="{{ lang_trans('entry_meta_keyword') }}" id="input-meta-keywordmeta_keyword" class="form-control">{{ $meta_keyword }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box-body -->
    </div>
@endsection
@push('stylesheet')
    <link rel="stylesheet" href="{{ load_asset('plugins/tags-input/src/jquery.tagsinput.css') }}">
@endpush
@push('script_footer')
    <script src="{{ load_asset('plugins/tags-input/src/jquery.tagsinput.js') }}"></script>

    <script src="{{ load_public_asset('javascript/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ load_public_asset('javascript/plugins/ckeditor/config-ckeditor.js') }}"></script>
    <script type="text/javascript">
        filemanagerRender('input-description');
        $('#input-tag').tagsInput({width: 'auto'});
    </script>
    <script>
        $('input[name=\'name\']').on('change blur paste', function () {
            if ($(this).val() !== '') {
                var urlSuffix = filterMark($(this).val());
                @if($suffix)
                    urlSuffix += '-{{ $suffix }}';
                @endif
                $('input[name=\'url_alias\']').val(urlSuffix);
            } else {
                $('input[name=\'url_alias\']').val('');
            }
        });
    </script>
    <script type="text/javascript"><!--
        // Category
        $('input[name=\'category\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: '{{ site_url('article/category/auto-complete') }}?filter_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['name'],
                                value: item['id']
                            }
                        }));
                    }
                });
            },
            'select': function (item) {
                $('input[name=\'category\']').val('');
                $('#article-category' + item['value']).remove();
                $('#article-category').append('<div id="article-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="article_category[]" value="' + item['value'] + '" /></div>');
            }
        });
        $('#article-category').delegate('.fa-minus-circle', 'click', function () {
            $(this).parent().remove();
        });
        //--></script>
@endpush
