<header class="mdc-top-app-bar">
    <div class="mdc-top-app-bar__row">
        <div class="mdc-top-app-bar__section mdc-top-app-bar__section--align-start">
            <button class="material-icons mdc-top-app-bar__navigation-icon mdc-icon-button sidebar-toggler">menu</button>
            <span class="mdc-top-app-bar__title">
                @hasSection('heading_title')
                    @yield('heading_title')
                @endif
            </span>
        </div>
        <div class="mdc-top-app-bar__section mdc-top-app-bar__section--align-end mdc-top-app-bar__section-right">
            <div class="menu-button-container menu-profile d-none d-md-block">
                <button class="mdc-button mdc-menu-button">
                <span class="d-flex align-items-center">
                  <span class="figure">
                    <img src="{{ $avatar }}" alt="user" class="user">
                  </span>
                  <span class="user-name">{{ $full_name }} <i class="mdc-drawer-arrow material-icons">chevron_right</i></span>
                </span>
                </button>
                <div class="mdc-menu mdc-menu-surface" tabindex="-1">
                    <ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical">
                        <li class="mdc-list-item" role="menuitem" onclick="location = '{{ site_url('customer/profile') }}'">
                            <div class="item-thumbnail item-thumbnail-icon-only">
                                <i class="mdi mdi-account-edit-outline text-primary"></i>
                            </div>
                            <div class="item-content d-flex align-items-start flex-column justify-content-center">
                                <h6 class="item-subject font-weight-normal">Cập nhật tài khoản</h6>
                            </div>
                        </li>
                        <li class="mdc-list-item" role="menuitem" onclick="location = '{{ site_url('auth/logout') }}'">
                            <div class="item-thumbnail item-thumbnail-icon-only">
                                <i class="mdi mdi-logout-variant text-primary"></i>
                            </div>
                            <div class="item-content d-flex align-items-start flex-column justify-content-center">
                                <h6 class="item-subject font-weight-normal">Đăng xuất</h6>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="divider d-none d-md-block"></div>
            <div class="menu-button-container d-md-block">
                <a class="mdc-button mdc-menu-button" href="{{ site_url("auth/logout") }}">
                    <i class="mdi mdi-logout-variant"></i> &nbsp;<span class="d-none d-md-block">Đăng xuất</span>
                </a>
            </div>
        </div>
    </div>
</header>
