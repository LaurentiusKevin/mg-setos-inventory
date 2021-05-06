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
</head>
<body class="c-app flex-row align-items-center">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card-group">
                <div class="card p-4 text-white" style="background-color: #6C3D94">
                    <div class="card-body">
                        <h1>Login</h1>
                        <p class="text-muted">Sign In to your account</p>
                        <form id="formData">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="c-icon fas fa-user"></i>
                                </span>
                                </div>
                                <input class="form-control" type="text" placeholder="Username" id="username">
                            </div>
                            <div class="input-group mb-4">
                                <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="c-icon fas fa-lock"></i>
                                </span>
                                </div>
                                <input class="form-control" type="password" placeholder="Password" id="password">
                            </div>

{{--                            <div class="custom-control custom-checkbox text-right">--}}
{{--                                <input type="checkbox" class="custom-control-input" id="lihatPassword">--}}
{{--                                <label class="custom-control-label" for="lihatPassword">Lihat Password</label>--}}
{{--                            </div>--}}
                            <div class="d-flex flex-row-reverse bd-highlight">
                                <div class="p-2 bd-highlight">
                                    <button class="btn btn-outline-light px-4" type="submit" id="btn-submit">Login</button>
                                </div>
                            </div>

{{--                            <div class="row justify-content-end mt-5">--}}
{{--                                <div class="col-6 text-right">--}}
{{--                                    <button class="btn btn-primary px-4" type="submit" id="btn-submit">Login</button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </form>
                    </div>
                </div>
                <div class="card text-white bg-white py-5 d-md-down-none" style="width:44%">
                    <div class="d-flex align-content-center flex-wrap" style="height: 100%">
                        <div class="p-2 bd-highlight">
                            <img class="img-fluid" alt="logo" src="{{ asset('icons/logo-mg-setos-hotel.png') }}" style="top: 50%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/jquery.js') }}"></script>
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

    const usernameVal = () => document.getElementById('username').value;
    const passwordVal = () => document.getElementById('password').value;

    document.getElementById('formData').addEventListener('submit', event => {
        event.preventDefault();
        Loader.button('#btn-submit', 'spinner-border spinner-border-sm mr-2');

        axios
            .post('{{ route('admin.api.submit') }}', {
                username: usernameVal(),
                password: passwordVal()
            })
            .then(function (response) {
                Loader.button('#btn-submit', 'spinner-border spinner-border-sm mr-2');
                if (response.data.status === 'success') {
                    location.reload();
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Gagal Login',
                        text: response.data.message
                    })
                }
            })
            .catch(function (error) {
                Loader.button('#btn-submit', 'spinner-border spinner-border-sm mr-2');
                Swal.fire({
                    icon: 'warning',
                    title: 'Gagal Login'
                })
            });
    });
</script>
</body>
</html>
