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
    <style>
        /*.c-sidebar .c-sidebar-brand, .c-sidebar .c-sidebar-header {*/
        /*    background: #331c45;*/
        /*}*/
        /*.c-sidebar {*/
        /*    position: relative;*/
        /*    display: -ms-flexbox;*/
        /*    display: flex;*/
        /*    -ms-flex: 0 0 256px;*/
        /*    flex: 0 0 256px;*/
        /*    -ms-flex-direction: column;*/
        /*    flex-direction: column;*/
        /*    -ms-flex-order: -1;*/
        /*    order: -1;*/
        /*    width: 256px;*/
        /*    padding: 0;*/
        /*    box-shadow: none;*/
        /*    color: #fff;*/
        /*    background: #6C3D94;*/
        /*    transition: box-shadow 0.3s 0.15s, margin-left 0.3s, margin-right 0.3s, width 0.3s, z-index 0s ease 0.3s, -webkit-transform 0.3s;*/
        /*    transition: box-shadow 0.3s 0.15s, transform 0.3s, margin-left 0.3s, margin-right 0.3s, width 0.3s, z-index 0s ease 0.3s;*/
        /*    transition: box-shadow 0.3s 0.15s, transform 0.3s, margin-left 0.3s, margin-right 0.3s, width 0.3s, z-index 0s ease 0.3s, -webkit-transform 0.3s;*/
        /*}*/
        .sk-chase {
            width: 40px;
            height: 40px;
            position: relative;
            animation: sk-chase 2.5s infinite linear both;
        }

        .sk-chase-dot {
            width: 100%;
            height: 100%;
            position: absolute;
            left: 0;
            top: 0;
            animation: sk-chase-dot 2.0s infinite ease-in-out both;
        }

        .sk-chase-dot:before {
            content: '';
            display: block;
            width: 25%;
            height: 25%;
            background-color: #6C3D94;
            border-radius: 100%;
            animation: sk-chase-dot-before 2.0s infinite ease-in-out both;
        }

        .sk-chase-dot:nth-child(1) { animation-delay: -1.1s; }
        .sk-chase-dot:nth-child(2) { animation-delay: -1.0s; }
        .sk-chase-dot:nth-child(3) { animation-delay: -0.9s; }
        .sk-chase-dot:nth-child(4) { animation-delay: -0.8s; }
        .sk-chase-dot:nth-child(5) { animation-delay: -0.7s; }
        .sk-chase-dot:nth-child(6) { animation-delay: -0.6s; }
        .sk-chase-dot:nth-child(1):before { animation-delay: -1.1s; }
        .sk-chase-dot:nth-child(2):before { animation-delay: -1.0s; }
        .sk-chase-dot:nth-child(3):before { animation-delay: -0.9s; }
        .sk-chase-dot:nth-child(4):before { animation-delay: -0.8s; }
        .sk-chase-dot:nth-child(5):before { animation-delay: -0.7s; }
        .sk-chase-dot:nth-child(6):before { animation-delay: -0.6s; }

        @keyframes sk-chase {
            100% { transform: rotate(360deg); }
        }

        @keyframes sk-chase-dot {
            80%, 100% { transform: rotate(360deg); }
        }

        @keyframes sk-chase-dot-before {
            50% {
                transform: scale(0.4);
            } 100%, 0% {
                  transform: scale(1.0);
              }
        }
    </style>
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
        button: function (btnSelector, classData, text = null) {
            let btn = document.querySelector(btnSelector);

            if (text !== null) {
                btn.innerHTML = text;
            }

            let nodes = () => {
                let li = document.createElement('span');
                li.id = 'btn-loader';
                li.className = classData;
                li.setAttribute('role','status');
                li.setAttribute('aria-hidden','true');
                return li;
            };

            if (document.getElementById('btn-loader')) {
                document.getElementById('btn-loader').remove();
                btn.removeAttribute('disabled')
            } else {
                btn.prepend(nodes());
                btn.setAttribute('disabled',true)
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

    const LoaderV2 = {
        button: function (btnSelector, classData, text = null) {
            // let btn = document.querySelector(btnSelector);

            if (text !== null) {
                btnSelector.innerHTML = text;
            }

            let nodes = () => {
                let li = document.createElement('span');
                li.id = 'btn-loader';
                li.className = classData;
                li.setAttribute('role','status');
                li.setAttribute('aria-hidden','true');
                return li;
            };

            if (document.getElementById('btn-loader')) {
                document.getElementById('btn-loader').remove();
                btnSelector.removeAttribute('disabled')
            } else {
                btnSelector.prepend(nodes());
                btnSelector.setAttribute('disabled',true)
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
</script>
@yield('script')
</body>
</html>
