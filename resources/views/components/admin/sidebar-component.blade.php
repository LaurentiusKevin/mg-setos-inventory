<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
    <div class="c-sidebar-brand d-lg-down-none">
        <div class="c-sidebar-brand-full">
            <img class="img-fluid" src="{{ asset('icons/logo-mg-setos-hotel.webp') }}" alt="logo-mg-setos" style="width: 118px">
        </div>
        <div class="c-sidebar-brand-minimized">
            <img class="img-fluid" src="{{ asset('icons/logo-mg-setos-hotel.webp') }}" alt="logo-mg-setos" style="width: 46px">
        </div>
    </div>
    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ url('admin') }}">
                <i class="fa fa-tachometer-alt c-sidebar-nav-icon"></i> Dashboard
            </a>
        </li>
        <li class="c-sidebar-nav-title">Menu</li>
        @foreach($sidebar AS $item)
            <li class="c-sidebar-nav-dropdown">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="c-sidebar-nav-icon {{ $item['group']['icon'] }}"></i>
                    {{ $item['group']['name'] }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @foreach($item['menu'] AS $menu)
                        <li class="c-sidebar-nav-item">
                            <a class="c-sidebar-nav-link" href="{{ url($menu->url) }}">
                                <span class="c-sidebar-nav-icon"></span> {{ $menu->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
    <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
</div>
