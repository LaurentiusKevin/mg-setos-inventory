<header class="c-header c-header-light c-header-fixed c-header-with-subheader">
    <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
        <i class="fas fa-bars c-icon c-icon-lg"></i>
    </button>
    <a class="c-header-brand d-lg-none" href="#">
{{--        <svg width="118" height="46" alt="CoreUI Logo">--}}
{{--            <use xlink:href="assets/brand/coreui.svg#full"></use>--}}
{{--        </svg>--}}
    </a>
    <button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true">
        <i class="fas fa-bars c-icon c-icon-lg"></i>
    </button>
    <ul class="c-header-nav d-md-down-none">
        <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="#">Dashboard</a></li>
        <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="#">Profile</a></li>
    </ul>
    <ul class="c-header-nav ml-auto mr-4">
{{--            <li class="c-header-nav-item d-md-down-none mx-2">--}}
{{--                <a class="c-header-nav-link" href="#">--}}
{{--                    <i class="fas fa-bell c-icon"></i>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li class="c-header-nav-item d-md-down-none mx-2">--}}
{{--                <a class="c-header-nav-link" href="#">--}}
{{--                    <i class="fas fa-list c-icon"></i>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li class="c-header-nav-item d-md-down-none mx-2">--}}
{{--                <a class="c-header-nav-link" href="#">--}}
{{--                    <i class="fas fa-envelope-open c-icon"></i>--}}
{{--                </a>--}}
{{--            </li>--}}
        <li class="c-header-nav-item dropdown">
            <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <div class="c-avatar">
                    <img class="c-avatar-img" src="{{ asset('icons/avatars/man-18.svg') }}" alt="user@email.com">
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right pt-0">
                <div class="dropdown-header bg-info py-2"><strong class="text-white">{{ auth()->user()['name'] }}</strong></div>
                <div class="dropdown-header bg-light py-2"><strong>Settings</strong></div>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user c-icon mr-2"></i>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('admin.logout') }}">
                    <i class="fas fa-sign-out-alt c-icon mr-2 text-danger"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
    <div class="c-subheader px-3">
        <ol class="breadcrumb border-0 m-0">
            <li class="breadcrumb-item"><a href="{{ url('admin') }}">Admin</a></li>
            @yield('breadcrumb')
        </ol>
    </div>
</header>
