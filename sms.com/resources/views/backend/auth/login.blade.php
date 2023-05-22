<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ load_asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ load_asset('font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ load_asset('dist/css/admin.min.css') }}">
    <link rel="stylesheet" href="{{ load_asset('plugins/iCheck/square/blue.css') }}">
</head>
<body class="hold-transition skin-blue login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>WDS-ADMIN</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">{{ lang_trans('text_heading') }}</p>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session()->has('error_username_password'))
            <div class="alert alert-danger">
                <ul>
                    <li>{{ session('error_username_password') }}</li>
                </ul>
            </div>
        @endif

        <form action="{{ site_url('auth/login') }}" method="post">
            {{ csrf_field() }}
            <div class="form-group has-feedback">
                <input type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="{{ lang_trans('text_username') }}">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" value="" class="form-control" placeholder="{{ lang_trans('text_password') }}">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox"> {{ lang_trans('text_remember') }}
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{ lang_trans('button_login') }}</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<script src="{{ load_asset('plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
<script src="{{ load_asset('bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ load_asset('plugins/iCheck/icheck.min.js') }}"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
</body>
</html>
