function filterMark(str){
    var filter = str.toLowerCase();
    filter = filter.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
    filter = filter.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
    filter = filter.replace(/ì|í|ị|ỉ|ĩ/g, "i");
    filter = filter.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
    filter = filter.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
    filter = filter.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
    filter = filter.replace(/đ/g, "d");
    filter = filter.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_/g, "-");
    filter = filter.replace(/-+-/g, "-");
    filter = filter.replace(/--/g, "-");
    filter = filter.replace(/  /g, "-");
    filter = filter.replace(/^\-+|\-+$/g, "");
    return filter;
}

$(document).ajaxSend(function (event, xhr, opt) {
    $('body').remove('#wds-ajax-progress-loader').append('<div id="wds-ajax-progress-loader"><div class="ajax-progress-loader"></div></div>');
    $('#wds-ajax-progress-loader').show();
});

$(document).ajaxComplete(function (event, xhr, opt) {
    setTimeout(function (e) {
        $('body').remove('#wds-ajax-progress-loader');
        $('#wds-ajax-progress-loader').hide();
    }, 100);
});

$(document).ready(function() {
    // tooltips on hover
    $('[data-toggle=\'tooltip\']').tooltip({container: 'body', html: true});
    // Makes tooltips work on ajax generated content
    $(document).ajaxStop(function() {
        $('[data-toggle=\'tooltip\']').tooltip({container: 'body'});
    });
    // tooltip remove
    $('[data-toggle=\'tooltip\']').on('remove', function() {
        $(this).tooltip('destroy');
    });
    // Tooltip remove fixed
    $(document).on('click', '[data-toggle=\'tooltip\']', function(e) {
        $('body > .tooltip').remove();
    });
    //Form Submit for IE Browser
    $('button[type=\'submit\']').on('click', function() {
        $("form[id*='form-']").submit();
    });
    // Image Manager
    $(document).on('click', 'a[data-toggle=\'image\']', function(e) {
        var $element = $(this);
        var $popover = $element.data('bs.popover'); // element has bs popover?
        e.preventDefault();
        // destroy all image popovers
        $('a[data-toggle="image"]').popover('destroy');

        // remove flickering (do not re-add popover when clicking for removal)
        if ($popover) {
            return;
        }
        $element.popover({
            html: true,
            placement: 'right',
            trigger: 'manual',
            content: function() {
                return '<button type="button" id="button-image" class="btn btn-primary"><i class="fa fa-pencil"></i></button> <button type="button" id="button-clear" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>';
            }
        });
        $element.popover('show');
        $('#button-image').on('click', function() {
            var $button = $(this);
            var $icon   = $button.find('> i');
            var directory = '';
            if (typeof(Storage) !== 'undefined' && (typeof(localStorage.getItem('directory')) !== 'undefined') && (localStorage.getItem('directory') !== '')) {
                directory = localStorage.getItem('directory');
            }
            $('#modal-image').remove();
            $.ajax({
                url: siteUrl + 'common/file-manager'  + '?target=' + $element.parent().find('input').attr('id') + '&thumb=' + $element.attr('id') + '&directory=' + directory,
                dataType: 'html',
                beforeSend: function() {
                    $button.prop('disabled', true);
                    if ($icon.length) {
                        $icon.attr('class', 'fa fa-circle-o-notch fa-spin');
                    }
                },
                complete: function() {
                    $button.prop('disabled', false);
                    if ($icon.length) {
                        $icon.attr('class', 'fa fa-pencil');
                    }
                },
                success: function(html) {
                    $('body').append('<div id="modal-image" class="modal" data-backdrop="static" data-keyboard="false">' + html + '</div>');
                    $('#modal-image').modal('show');
                }
            });
            $element.popover('destroy');
        });

        $('#button-clear').on('click', function() {
            $element.find('img').attr('src', $element.find('img').attr('data-placeholder'));
            $element.parent().find('input').val('');
            $element.popover('destroy');
        });
    });
});

function changeAjaxStatus(url, type, status) {
    _data_ajax = 'status';
    if ((typeof status !== 'undefined') && status === 'featured'){
        _data_ajax = 'featured';
    }
    if ((typeof status !== 'undefined') && status === 'top'){
        _data_ajax = 'top';
    }
    $('[data-ajax=\'' + _data_ajax + '\']').bind('click', function (){
        var $this = $(this);
        if (typeof type !== 'undefined'){
            if (type === 'category_id' || type === 'article_id' || type === 'product_id' ||
                type === 'information_id' || type === 'banner_id' || type === 'customer_id'){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url       : url,
                    type      : 'post',
                    dataType  : 'json',
                    data      : 'value=' + encodeURIComponent($this.attr('_status')),
                    beforeSend: function (){
                        $('.success, .warning').remove();
                        $this.find('i.fa').attr('class', 'fa fa-spinner fa-spin');
                    },
                    success   : function (data){
                        if (data.success){
                            if (data.status){
                                $this.attr({
                                    'class': 'enabled',
                                    'title': 'Kích hoạt',
                                    'data-original-title': 'Kích hoạt',
                                    '_status'  : data[type] + ',' + data.status
                                });
                                $this.find('i.fa').attr('class', 'fa fa-check-circle');
                            } else{
                                $this.attr({
                                    'class': 'disabled',
                                    'title': 'Vô hiệu hóa',
                                    'data-original-title': 'Vô hiệu hóa',
                                    '_status'  : data[type] + ',' + data.status
                                });
                                $this.find('i.fa').attr('class', 'fa fa-times-circle');
                            }
                        }
                        if (data.error){
                            $('.content-header').after('<div class="alert alert-danger">' + data.error + '</div>');
                            if (data.status){
                                $this.find('i.fa').attr('class', 'fa fa-times-circle');
                            } else{
                                $this.find('i.fa').attr('class', 'fa fa-check-circle');
                            }
                        }
                    }
                });
            }
        }
    });
}

function formatCurrency(number) {
    return (
        Number(number)
            .toFixed(0) // always 0 decimal digits
            .replace('.', ',') // replace decimal point character with ,
            .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.') + ' đ'
    ) // use . as a separator
}

// Autocomplete */
(function($) {
    $.fn.autocomplete = function(option) {
        return this.each(function() {
            var $this = $(this);
            var $dropdown = $('<ul class="dropdown-menu" />');

            this.timer = null;
            this.items = [];

            $.extend(this, option);

            $this.attr('autocomplete', 'off');

            // Focus
            $this.on('focus', function() {
                this.request();
            });

            // Blur
            $this.on('blur', function() {
                setTimeout(function(object) {
                    object.hide();
                }, 300, this);
            });

            // Keydown
            $this.on('keydown', function(event) {
                switch(event.keyCode) {
                    case 27: // escape
                        this.hide();
                        break;
                    default:
                        this.request();
                        break;
                }
            });

            // Click
            this.click = function(event) {
                event.preventDefault();

                var value = $(event.target).parent().attr('data-value');

                if (value && this.items[value]) {
                    this.select(this.items[value]);
                }
            }

            // Show
            this.show = function() {
                var pos = $this.position();

                $dropdown.css({
                    top: pos.top + $this.outerHeight(),
                    left: pos.left
                });

                $dropdown.show();
            }

            // Hide
            this.hide = function() {
                $dropdown.hide();
            }

            // Request
            this.request = function() {
                clearTimeout(this.timer);

                this.timer = setTimeout(function(object) {
                    object.source($(object).val(), $.proxy(object.response, object));
                }, 200, this);
            }

            // Response
            this.response = function(json) {
                var html = '';
                var category = {};
                var name;
                var i = 0, j = 0;

                if (json.length) {
                    for (i = 0; i < json.length; i++) {
                        // update element items
                        this.items[json[i]['value']] = json[i];

                        if (!json[i]['category']) {
                            // ungrouped items
                            html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
                        } else {
                            // grouped items
                            name = json[i]['category'];
                            if (!category[name]) {
                                category[name] = [];
                            }

                            category[name].push(json[i]);
                        }
                    }

                    for (name in category) {
                        html += '<li class="dropdown-header">' + name + '</li>';

                        for (j = 0; j < category[name].length; j++) {
                            html += '<li data-value="' + category[name][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[name][j]['label'] + '</a></li>';
                        }
                    }
                }

                if (html) {
                    this.show();
                } else {
                    this.hide();
                }

                $dropdown.html(html);
            }

            $dropdown.on('click', '> li > a', $.proxy(this.click, this));
            $this.after($dropdown);
        });
    }
})(window.jQuery);
