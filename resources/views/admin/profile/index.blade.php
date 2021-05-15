@extends('admin._layout')

@section('title','Profile')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item active">Profile</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-5 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <strong>Profile Detail</strong>
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset('icons/avatars/businessman.svg') }}" alt="Stacie Hall" class="img-fluid rounded-circle mb-2" width="128" height="128">
                    <h5 class="card-title mb-0">{{ $user_info->name }}</h5>
                    <div class="text-muted mb-2">{{ $user_info->department_name ?? 'not in any department' }}</div>
                </div>
                <div class="card-body bg-gradient-light">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-1">
                            <span class="fas fa-user fa-fw me-1"></span>
                            {{ $user_info->username ?? '-' }}
                        </li>
                        <li class="mb-1">
                            <span class="fas fa-envelope fa-fw me-1"></span>
                            {{ $user_info->email ?? '-' }}
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <h5 class="h6 card-title">Terdaftar Pada</h5>
                    {{ date('d F Y (H:i:s)',strtotime($user_info->created_at)) }}
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-7 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <strong>Edit Profile</strong>
                </div>
                <div class="card-body">
                    <form id="f-e-profile">
                        <div class="form-group row">
                            <label for="i-username" class="col-sm-2 col-form-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="i-username" value="{{ $user_info->username }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="i-name" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="i-name" value="{{ $user_info->name }}" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" id="f-e-profile-submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <strong>Change Password</strong>
                </div>
                <div class="card-body">
                    <form id="f-e-password">
                        <div class="form-group row">
                            <label for="input-old-password" class="col-sm-2 col-form-label">Old Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="input-old-password" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="input-password" class="col-sm-2 col-form-label">New Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="input-password" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="input-repeat-password" class="col-sm-2 col-form-label">Repeat Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="input-repeat-password" required>
                                <small class="text-danger d-none" id="password-different">Password berbeda</small>
                                <small class="text-success d-none" id="password-correct">Password sama</small>
                            </div>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="lihat-password">
                            <label class="form-check-label" for="lihat-password">Lihat Password</label>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" id="f-e-password-submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/moment.js') }}"></script>
<script type="text/javascript">
    let f_e_profile = document.getElementById('f-e-profile');
    let f_e_password = document.getElementById('f-e-password');

    let i_username = document.getElementById('i-username');
    let i_name = document.getElementById('i-name');

    let showPassword = document.getElementById('lihat-password');
    let input_old_password = document.getElementById('input-old-password');
    let input_password = document.getElementById('input-password');
    let input_repeat_password = document.getElementById('input-repeat-password');
    let password_different = document.getElementById('password-different');
    let password_correct = document.getElementById('password-correct');

    f_e_profile.addEventListener('submit', async event => {
        event.preventDefault();
        Loader.button('#f-e-profile-submit', 'spinner-border spinner-border-sm mr-2');

        await axios({
            url: '{{ route('admin.profile.api.store-profile') }}',
            method: 'post',
            data: {
                username: i_username.value,
                name: i_name.value
            }
        }).then(response => {
            if (response.data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Tersimpan!',
                    timer: 1200,
                    showConfirmButton: false,
                    didClose() {
                        location.reload()
                    }
                })
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Gagal Tersimpan!',
                    text: response.data.message
                })
            }
        }).catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Terdapat kesalahan pada sistem!',
                text: error.response.data.message
            })
        })

        Loader.button('#f-e-profile-submit', 'spinner-border spinner-border-sm mr-2');
    });

    f_e_password.addEventListener('submit', async event => {
        event.preventDefault();
        Loader.button('#f-e-password-submit', 'spinner-border spinner-border-sm mr-2');

        await axios({
            url: '{{ route('admin.profile.api.store-password') }}',
            method: 'post',
            data: {
                old_password: input_old_password.value,
                new_password: input_password.value,
                repeated_new_password: input_repeat_password.value
            }
        }).then(response => {
            if (response.data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Tersimpan!',
                    timer: 1200,
                    showConfirmButton: false,
                    didClose() {
                        location.reload()
                    }
                })
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Gagal Tersimpan!',
                    text: response.data.message
                })
            }
        }).catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Terdapat kesalahan pada sistem!',
                text: error.response.data.message
            })
        })

        Loader.button('#f-e-password-submit', 'spinner-border spinner-border-sm mr-2');
    });

    let checkPassword = () => {
        if (input_repeat_password.value !== '') {
            if (input_password.value === input_repeat_password.value) {
                password_different.classList.add('d-none');
                password_correct.classList.remove('d-none');
            } else {
                password_different.classList.remove('d-none');
                password_correct.classList.add('d-none');
            }
        } else {
            password_different.classList.add('d-none');
            password_correct.classList.add('d-none');
        }
    }

    input_password.addEventListener('keyup', event => {
        checkPassword();
    });

    input_repeat_password.addEventListener('keyup', event => {
        checkPassword();
    });

    showPassword.addEventListener('change', event => {
        if (showPassword.checked === true) {
            input_old_password.setAttribute('type','text')
            input_password.setAttribute('type','text')
            input_repeat_password.setAttribute('type','text')
        } else {
            input_old_password.setAttribute('type','password')
            input_password.setAttribute('type','password')
            input_repeat_password.setAttribute('type','password')
        }
    });
</script>
@endsection
