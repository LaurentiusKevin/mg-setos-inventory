@extends('admin._layout')

@section('title','Master Data - User Aplikasi - Create')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item">User Aplikasi</li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('style')
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>Create New User</strong>
                </div>
                <form id="formData">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="iRole">Role <span class="text-danger">*</span></label>
                            <select class="form-control" name="master_roles_id" id="iRole" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($role as $r)
                                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="iName">Name <span class="text-danger">*</span></label>
                            <input id="iName" name="name" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="iEmail">Email</label>
                            <input id="iEmail" name="email" type="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="iUsername">Username <span class="text-danger">*</span></label>
                            <input id="iUsername" name="username" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="iPassword">Password <span class="text-danger">*</span></label>
                            <input id="iPassword" name="password" type="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="card-footer bg-gradient-secondary">
                        <div class="row justify-content-between">
                            <div class="col-sm-12 col-md-4 col-lg-2">
                                <a href="{{ route('admin.master-data.user-aplikasi.view.index') }}" class="btn btn-block btn-outline-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-2 mt-2 mt-lg-0 mt-sm-2">
                                <button type="submit" class="btn btn-block btn-success"><i class="fas fa-check mr-2"></i>Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        const role = () => document.getElementById('iRole').value
        const name = () => document.getElementById('iName').value
        const email = () => document.getElementById('iEmail').value
        const username = () => document.getElementById('iUsername').value
        const password = () => document.getElementById('iPassword').value

        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById('formData').addEventListener('submit', event => {
                event.preventDefault()

                axios({
                    url: '{{ route('admin.master-data.user-aplikasi.api.store') }}',
                    method: 'post',
                    data: {
                        role: role(),
                        name: name(),
                        email: email(),
                        username: username(),
                        password: password(),
                    }
                }).then(response => {
                    if (response.data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Data Tersimpan',
                            timer: 1200,
                            showConfirmButton: false,
                            willClose(popup) {
                                window.location = response.data.redirect
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: response.data.message
                        });
                    }
                }).catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terdapat Kesalahan Pada System',
                        text: error.response.data.message,
                    });
                })
            });
        });
    </script>
@endsection
