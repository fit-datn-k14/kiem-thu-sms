<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml" lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        @hasSection('heading_title')
            @yield('heading_title')
        @endif
    </title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/png" href="{{ load_asset('images/favicon.png') }}"/>
    <link rel="stylesheet" href="{{ load_asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ load_asset('font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ load_asset('icons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ load_asset('plugins/iCheck/flat/blue.css') }}">
    <link rel="stylesheet" href="{{ load_asset('plugins/morris/morris.css') }}">
    <link rel="stylesheet" href="{{ load_asset('plugins/jvectormap/jquery-jvectormap-1.2.2.css') }}">
    <link rel="stylesheet" href="{{ load_asset('plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ load_asset('plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ load_asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
    <link rel="stylesheet" href="{{ load_asset('plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ load_asset('plugins/colorpicker/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ load_asset('plugins/timepicker/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ load_asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ load_asset('dist/css/admin.min.css') }}">
    <link rel="stylesheet" href="{{ load_asset('dist/css/skin.min.css') }}">
    <link rel="stylesheet" href="{{ load_asset('dist/css/style.css') }}">
    @stack('stylesheet')
    <script>var siteUrl = '{{ site_url() }}/';</script>
    <script src="{{ load_asset('plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
    @stack('script_head')
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    @include('backend.layout.header')
    @include('backend.layout.sidebar')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @hasSection('heading_title')
                    @yield('heading_title')
                @endif
            </h1>
            <ol class="breadcrumb">
                @if(isset($breadcrumbs))
                    @foreach($breadcrumbs as $breadcrumb)
                         <li><a href="{{ $breadcrumb['href'] }}">{{ $breadcrumb['name'] }}</a></li>
                    @endforeach
                @endif
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div id="notification">
                @include('backend.layout.flash_message')
            </div>
            @yield('content')
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    @include('backend.layout.footer')
</div>
<!-- ./wrapper -->
<script src="{{ load_asset('plugins/jQueryUI/jquery-ui.min.js') }}"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="{{ load_asset('bootstrap/js/bootstrap.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
{{--<script src="{{ load_asset('backend') }}/plugins/morris/morris.min.js"></script>--}}
<script src="{{ load_asset('plugins/sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ load_asset('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ load_asset('plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ load_asset('plugins/knob/jquery.knob.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="{{ load_asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ load_asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ load_asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<script src="{{ load_asset('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ load_asset('plugins/fastclick/fastclick.js') }}"></script>
<script src="{{ load_asset('plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ load_asset('plugins/colorpicker/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ load_asset('plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ load_asset('plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
{{--<script src="{{ load_asset('backend') }}/dist/js/pages/dashboard.js"></script>--}}
<script src="{{ load_asset('dist/js/app.min.js') }}"></script>
<script src="{{ load_asset('javascript/script.js') }}"></script>
<script src="{{ load_asset('javascript/common.js') }}"></script>
@stack('script_footer')
</body>
</html>
