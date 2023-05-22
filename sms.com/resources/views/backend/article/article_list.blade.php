@extends('backend.layout.master')
@section('heading_title', lang_trans('heading_title'))
@section('content')
    <div class="box box-primary color-palette-box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-folder-open"></i> {{ lang_trans('heading_title') }}</h3>
            <div class="pull-right">
                <a href="{{ $add }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> {{ lang_trans('button_add') }}
                </a>
                <a onclick="confirm('{{ lang_trans('text_confirm') }}') ? $('#form').submit() : false;" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> {{ lang_trans('button_delete') }}
                </a>
            </div>
        </div>
        <div class="box-body">
            <div class="well well-sm">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label" for="input-name">{{ lang_trans('entry_name') }}</label>
                            <input type="text" name="filter_name" value="{{ $filter_name }}" placeholder="{{ lang_trans('entry_name') }}" id="input-name" class="form-control"/>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label" for="input-category">{{ lang_trans('entry_category') }}</label>
                            <select name="filter_category_id" id="input-category" class="form-control">
                                <option value="0"></option>
                                @foreach($categories as $category)
                                    @if($category['id'] == $filter_category_id)
                                        <option value="{{ $category['id'] }}" selected="selected">{{ $category['name'] }}</option>
                                    @else
                                        <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label" for="input-featured">{{ lang_trans('entry_featured') }}</label>
                                    <select name="filter_featured" id="input-featured" class="form-control">
                                        <option value=""></option>
                                        @if($filter_featured == '1')
                                            <option value="1" selected="selected">{{ lang_trans('text_enabled') }}</option>
                                        @else
                                            <option value="1">{{ lang_trans('text_enabled') }}</option>
                                        @endif
                                        @if($filter_featured == '0')
                                            <option value="0" selected="selected">{{ lang_trans('text_disabled') }}</option>
                                        @else
                                            <option value="0">{{ lang_trans('text_disabled') }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label" for="input-status">{{ lang_trans('column_status') }}</label>
                                    <select name="filter_status" class="form-control">
                                        <option value=""></option>
                                        @if($filter_status == '1')
                                            <option value="1" selected="selected">{{ lang_trans('text_enabled') }}</option>
                                        @else
                                            <option value="1">{{ lang_trans('text_enabled') }}</option>
                                        @endif
                                        @if($filter_status == '0')
                                            <option value="0" selected="selected">{{ lang_trans('text_disabled') }}</option>
                                        @else
                                            <option value="0">{{ lang_trans('text_disabled') }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="hidden-sm hidden-xs" style="margin-bottom:25px;"></div>
                        <button type="button" id="button-filter" class="btn btn-primary">
                            <i class="fa fa-filter"></i> {{ lang_trans('button_filter') }}</button>
                    </div>
                </div>
            </div>
            <form action="{{ $delete }}" method="post" enctype="multipart/form-data" id="form">
                {{ csrf_field() }}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <td style="width: 1px;" class="text-center">
                                <input class="minimal check-all" type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);"/>
                            </td>
                            <td class="text-center">{{ lang_trans('column_image') }}</td>
                            <td class="text-left">
                                @if($sort == 'name')
                                    <a href="{{ $sort_name }}">{{ lang_trans('column_name') }} <i class="fa fa-caret-{{ $order == 'asc' ? 'up' : 'down' }}"></i></a>
                                @else
                                    <a href="{{ $sort_name }}">{{ lang_trans('column_name') }}</a>
                                @endif
                            </td>
                            <td class="text-left">{{ lang_trans('column_category') }}</td>
                            <td class="text-center">
                                @if($sort == 'featured')
                                    <a href="{{ $sort_featured }}">{{ lang_trans('column_featured') }} <i class="fa fa-caret-{{ $order == 'asc' ? 'up' : 'down' }}"></i></a>
                                @else
                                    <a href="{{ $sort_featured }}">{{ lang_trans('column_featured') }}</a>
                                @endif
                            </td>
                            <td class="text-center">{{ lang_trans('column_status') }}</td>
                            <td class="text-right">{{ lang_trans('column_action') }}</td>
                        </tr>
                        </thead>
                        <tbody>
                        @if($articles)
                            @foreach($articles as $article)
                                <tr>
                                    <td class="text-center">
                                        @if(in_array($article['id'], $selected))
                                            <input class="minimal check" type="checkbox" name="selected[]" value="{{ $article['id'] }}" checked="checked"/>
                                        @else
                                            <input class="minimal check" type="checkbox" name="selected[]" value="{{ $article['id'] }}"/>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($article['image'])
                                            <img src="{{ $article['image'] }}" alt="{{ $article['name'] }}" class="img-thumbnail"/>
                                        @else
                                            <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
                                        @endif
                                    </td>
                                    <td class="text-left">{{ $article['name'] }}</td>
                                    <td class="text-left">
                                        @if($article['article_categories'])
                                            <ul class="categories">
                                                @foreach($article['article_categories'] as $article_category)
                                                    <li>
                                                        <i class="fa fa-folder-open"></i> {{ $article_category['name'] }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a class="{{ $article['featured'] ? 'enabled' : 'disabled' }}" data-ajax="featured" _status="{{ $article['id'] }},{{ $article['featured'] }}" data-toggle="tooltip" title="{{ $article['featured'] ? lang_trans('text_disabled') : lang_trans('text_enabled') }}">
                                            @if($article['featured'])
                                                <i class="fa fa-check-circle"></i>
                                            @else
                                                <i class="fa fa-times-circle"></i>
                                            @endif
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a class="{{ $article['status'] ? 'enabled' : 'disabled' }}" data-ajax="status" _status="{{ $article['id'] }},{{ $article['status'] }}" data-toggle="tooltip" title="{{ $article['status'] ? lang_trans('text_disabled') : lang_trans('text_enabled') }}">
                                            @if($article['status'])
                                                <i class="fa fa-check-circle"></i>
                                            @else
                                                <i class="fa fa-times-circle"></i>
                                            @endif
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ $article['edit'] }}" data-toggle="tooltip" title="{{ lang_trans('button_edit') }}" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
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
            <div class="paginate">
                {!! $pagination !!}
            </div>
        </div>
        <!-- /.box-body -->
    </div>
@endsection
@push('script_footer')
    <script type="text/javascript"><!--
        changeAjaxStatus('{{ site_url('article/article/ajax-status') }}', 'article_id');
        changeAjaxStatus('{{ site_url('article/article/ajax-featured') }}', 'article_id', 'featured');
        //--></script>
    <script type="text/javascript"><!--
        $('#button-filter').on('click', function () {
            var url = '';

            var filter_name = $('input[name=\'filter_name\']').val();
            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }
            var filter_status = $('select[name=\'filter_status\']').val();
            if (filter_status !== '') {
                url += '&filter_status=' + encodeURIComponent(filter_status);
            }

            var filter_featured = $('select[name=\'filter_featured\']').val();
            if (filter_featured !== '') {
                url += '&filter_featured=' + encodeURIComponent(filter_featured);
            }

            var filter_category_id = $('select[name=\'filter_category_id\']').val();
            if (filter_category_id !== 0) {
                url += '&filter_category_id=' + encodeURIComponent(filter_category_id);
            }
            if  (url !== ''){
                location = '{{ site_url('article/article') }}?' + url;
            }else{
                location = '{{ site_url('article/article') }}';
            }
        });
        //--></script>
    <script type="text/javascript">
        $('input[name=\'filter_name\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: '{{ site_url('article/article/auto-complete') }}?filter_name=' + encodeURIComponent(request),
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
                $('input[name=\'filter_name\']').val(item['label']);
            }
        });

        //--></script>
@endpush
