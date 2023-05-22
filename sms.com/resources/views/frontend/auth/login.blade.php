<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="{{ load_asset('vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ load_asset('vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ load_asset('css/demo/style.css') }}">
    <link rel="shortcut icon" href="{{ load_asset('images/favicon.png') }}">
</head>
<body>
<script src="{{ load_asset('js/preloader.js') }}"></script>
<div class="body-wrapper">
    <div class="main-wrapper">
        <div class="page-wrapper full-page-wrapper d-flex align-items-center justify-content-center page-wrapper__auth">
            <main class="auth-page">
                <div class="mdc-layout-grid">
                    <div class="mdc-layout-grid__inner">
                        <div class="stretch-card mdc-layout-grid__cell--span-4-desktop mdc-layout-grid__cell--span-1-tablet"></div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop mdc-layout-grid__cell--span-6-tablet">
                            <div class="mdc-card mdc-card__auth">
                                <div class="login-box-msg text-center">{{ lang_trans('text_heading') }}</div>
                                @if ($errors->any())
                                    <div class="alert alert-danger" style="margin-top: 20px;">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if (session()->has('error_email_password'))
                                    <div class="alert alert-danger" style="margin-top: 20px;">
                                        <ul>
                                            <li>{{ session('error_email_password') }}</li>
                                        </ul>
                                    </div>
                                @endif
                                <form action="{{ site_url('auth/login') }}" method="post">
                                    {{ csrf_field() }}
                                    <div class="mdc-layout-grid">
                                        <div class="mdc-layout-grid__inner">
                                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                                                <div class="mdc-text-field w-100">
                                                    <input class="mdc-text-field__input" name="email" value="{{ old('email') }}" id="text-field-hero-input">
                                                    <div class="mdc-line-ripple"></div>
                                                    <label for="text-field-hero-input" class="mdc-floating-label">{{ lang_trans('text_email') }}</label>
                                                </div>
                                            </div>
                                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                                                <div class="mdc-text-field w-100">
                                                    <input class="mdc-text-field__input" name="password" type="password" id="text-field-hero-input">
                                                    <div class="mdc-line-ripple"></div>
                                                    <label for="text-field-hero-input" class="mdc-floating-label">{{ lang_trans('text_password') }}</label>
                                                </div>
                                            </div>
                                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                                                {{--<div class="mdc-form-field">
                                                    <div class="mdc-checkbox">
                                                        <input type="checkbox"
                                                               class="mdc-checkbox__native-control"
                                                               id="checkbox-1"/>
                                                        <div class="mdc-checkbox__background">
                                                            <svg class="mdc-checkbox__checkmark"
                                                                 viewBox="0 0 24 24">
                                                                <path class="mdc-checkbox__checkmark-path"
                                                                      fill="none"
                                                                      d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                                            </svg>
                                                            <div class="mdc-checkbox__mixedmark"></div>
                                                        </div>
                                                    </div>
                                                    <label for="checkbox-1">{{ lang_trans('text_remember') }}</label>
                                                </div>--}}
                                            </div>
                                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop d-flex align-items-center justify-content-end">
                                                <a href="{{ site_url('auth/forgot-password') }}">Quên mật khẩu?</a>
                                            </div>
                                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                                                <button type="submit" class="mdc-button mdc-button--raised w-100">
                                                    {{ lang_trans('button_login') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="stretch-card mdc-layout-grid__cell--span-4-desktop mdc-layout-grid__cell--span-1-tablet"></div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
<script src="{{ load_asset('vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ load_asset('js/material.js') }}"></script>
<script src="{{ load_asset('js/misc.js') }}"></script>
</body>
</html>
