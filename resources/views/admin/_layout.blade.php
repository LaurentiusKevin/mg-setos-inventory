<!DOCTYPE html>
<html lang="id">
<head>
    <base href="{{ url('/') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="MG Setos - Inventory - Stock Management System @hasSection('description') - @yield('description') @endif">
    <meta name="author" content="Laurentius Kevin">
    <meta name="keyword" content="MgSetos,Hotel,Inventory,StockManagement,System">
    <title>MG Setos @hasSection('title') - @yield('title') @endif</title>
    {{--    <link rel="apple-touch-icon" sizes="57x57" href="assets/favicon/apple-icon-57x57.png">--}}
    {{--    <link rel="apple-touch-icon" sizes="60x60" href="assets/favicon/apple-icon-60x60.png">--}}
    {{--    <link rel="apple-touch-icon" sizes="72x72" href="assets/favicon/apple-icon-72x72.png">--}}
    {{--    <link rel="apple-touch-icon" sizes="76x76" href="assets/favicon/apple-icon-76x76.png">--}}
    {{--    <link rel="apple-touch-icon" sizes="114x114" href="assets/favicon/apple-icon-114x114.png">--}}
    {{--    <link rel="apple-touch-icon" sizes="120x120" href="assets/favicon/apple-icon-120x120.png">--}}
    {{--    <link rel="apple-touch-icon" sizes="144x144" href="assets/favicon/apple-icon-144x144.png">--}}
    {{--    <link rel="apple-touch-icon" sizes="152x152" href="assets/favicon/apple-icon-152x152.png">--}}
    {{--    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-icon-180x180.png">--}}
    {{--    <link rel="icon" type="image/png" sizes="192x192" href="assets/favicon/android-icon-192x192.png">--}}
    {{--    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">--}}
    {{--    <link rel="icon" type="image/png" sizes="96x96" href="assets/favicon/favicon-96x96.png">--}}
    {{--    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">--}}
    {{--    <link rel="manifest" href="assets/favicon/manifest.json">--}}
    {{--    <meta name="msapplication-TileColor" content="#ffffff">--}}
    {{--    <meta name="msapplication-TileImage" content="assets/favicon/ms-icon-144x144.png">--}}
    {{--    <meta name="theme-color" content="#ffffff">--}}

    <link href="{{ asset('icons/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sweetalert2-default.css') }}" rel="stylesheet">
    @yield('style')
</head>
<body class="c-app">
<x-admin.sidebar-component></x-admin.sidebar-component>
<div class="c-wrapper c-fixed-components">
    <x-admin.navbar-component></x-admin.navbar-component>
    <div class="c-body">
        <main class="c-main">
            <div class="container-fluid">
                <div class="fade-in">
                    @yield('content')
                </div>
            </div>
        </main>
        <x-admin.footer-component></x-admin.footer-component>
    </div>
</div>
<script src="{{ asset('js/admin/coreui.bundle.min.js') }}"></script>
<script src="{{ asset('js/axios.js') }}"></script>
<script src="{{ asset('js/sweetalert2.js') }}"></script>
<script type="text/javascript">
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    const Loader = {
        button: function (btn, classData, text = null) {
            if (text !== null) {
                btn.html(text);
            }

            if (btn.hasClass(classData)) {
                btn.removeClass(classData);
                btn.prop("disabled", false);
            } else {
                btn.addClass(classData);
                btn.prop("disabled", true);
            }
        },
        label: function (label, classData) {
            if (label.hasClass(classData)) {
                label.removeClass(classData);
            } else {
                label.addClass(classData);
            }
        }
    }

    document.getElementsByClassName('action-logout').
</script>
@yield('script')
</body>
</html>
