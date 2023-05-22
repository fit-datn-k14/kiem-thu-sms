<aside class="mdc-drawer mdc-drawer--dismissible mdc-drawer--open">
    <div class="mdc-drawer__header">
        <a href="" class="brand-logo">
            <img src="{{ load_asset('images/logo.svg') }}" alt="logo">
        </a>
    </div>
    <div class="mdc-drawer__content">
        <div class="user-info">
            <p class="name">{{ $full_name }}</p>
            <p class="email">{{ $email }}</p>
        </div>
        <div class="mdc-list-group">
            <nav class="mdc-list mdc-drawer-menu">
                @if($navigation)
                    @foreach($navigation as $nav)
                        @if(isset($nav['children']) && $nav['children'])
                            <div class="mdc-list-item mdc-drawer-item">
                                <a class="mdc-expansion-panel-link" href="@if(isset($nav['route']) && $nav['route']){{ $nav['route'] }}@else{{ 'javascript:void(0)' }}@endif" data-toggle="expansionPanel" data-target="ui-sub-menu-m{{ $loop->index }}">
                                    <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">@if(isset($nav['icon']) && $nav['icon']){{ $nav['icon'] }}@endif</i>
                                    @if(isset($nav['name']) && $nav['name']){{ $nav['name'] }}@endif
                                    <i class="mdc-drawer-arrow material-icons">chevron_right</i>
                                </a>
                                <div class="mdc-expansion-panel @if(isset($nav['class_expanded']) && $nav['class_expanded']){{ $nav['class_expanded'] }}@endif" id="ui-sub-menu-m{{ $loop->index }}">
                                    <nav class="mdc-list mdc-drawer-submenu">
                                        @foreach($nav['children'] as $child)
                                        <div class="mdc-list-item mdc-drawer-item">
                                            <a class="@if(isset($child['class']) && $child['class']){{ $child['class'] }}@endif" href="@if(isset($child['route']) && $child['route']){{ $child['route'] }}@else{{ 'javascript:void(0)' }}@endif">
                                                <i class="material-icons">keyboard_arrow_right</i>
                                                @if(isset($child['name']) && $child['name']){{ $child['name'] }}@endif
                                            </a>
                                        </div>
                                        @endforeach
                                    </nav>
                                </div>
                            </div>
                        @else
                            <div class="mdc-list-item mdc-drawer-item">
                                <a class="@if(isset($nav['class']) && $nav['class']){{ $nav['class'] }}@endif"
                                   href="@if(isset($nav['route']) && $nav['route']){{ $nav['route'] }}@else{{ 'javascript:void(0)' }}@endif">
                                    <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">@if(isset($nav['icon']) && $nav['icon']){{ $nav['icon'] }}@endif</i>
                                    @if(isset($nav['name']) && $nav['name']){{ $nav['name'] }}@endif
                                </a>
                            </div>
                        @endif
                    @endforeach
                @endif
            </nav>
        </div>
    </div>
</aside>
