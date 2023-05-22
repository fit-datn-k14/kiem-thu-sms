<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @hasSection('heading_title')
            @yield('heading_title')
        @endif
    </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ load_asset('vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ load_asset('vendors/css/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ load_asset('vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ load_asset('vendors/jvectormap/jquery-jvectormap.css') }}">
    <link rel="stylesheet" href="{{ load_asset('css/demo/style.css') }}">
    @stack('stylesheet')
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ load_asset('images/favicon.png') }}">
    @stack('script_head')
    <script>var siteUrl = '{{ site_url() }}/';</script>
</head>
<body>
{{--<script src="{{ load_asset('js/preloader.js') }}"></script>--}}
<div class="body-wrapper">
    <!-- partial:partials/_sidebar.html -->
    @include('frontend.layout.sidebar')
    <!-- partial -->
    <div class="main-wrapper mdc-drawer-app-content">
        <!-- partial:partials/_navbar.html -->
        @include('frontend.layout.header')
        <!-- partial -->
        <div class="page-wrapper mdc-toolbar-fixed-adjust">
            <!-- Main content -->
            <div id="notification">
                @include('frontend.layout.flash_message')
            </div>
            <main class="content-wrapper">
                <div class="mdc-layout-grid">
                    @yield('content')
                </div>
            </main>
            <!-- partial:partials/_footer.html -->
            @include('frontend.layout.footer')
            <!-- partial -->
        </div>
    </div>
</div>
<!-- plugins:js -->
<script src="{{ load_asset('vendors/js/vendor.bundle.base.js') }}"></script>
<!-- endinject -->
<!-- Plugin js for this page-->
<script src="{{ load_asset('vendors/chartjs/Chart.min.js') }}"></script>
<script src="{{ load_asset('vendors/jvectormap/jquery-jvectormap.min.js') }}"></script>
<script src="{{ load_asset('vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<!-- End plugin js for this page-->
<!-- inject:js -->
<script src="{{ load_asset('js/material.js') }}"></script>
<script src="{{ load_asset('js/misc.js') }}"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="{{ load_asset('js/common.js') }}"></script>
@stack('script_footer')
<!-- End custom js for this page-->
</body>
</html>
