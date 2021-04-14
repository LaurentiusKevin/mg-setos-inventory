@extends('admin._layout')

@section('title','System Utility - Menu Group - Edit')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">System Utility</li>
    <li class="breadcrumb-item active">Menu Group</li>
@endsection

@section('style')
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>Edit Group Menu</strong>
                </div>
                <form id="formData">
                    <div class="card-body">
                        <input type="hidden" id="id" value="{{ $data->id }}">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label for="iRole">Role <span class="text-danger">*</span></label>
                                    <select class="form-control" name="master_roles_id" id="iRole" required>
                                        <option value="">-- Pilih Role --</option>
                                        @foreach($role as $item)
                                            <option value="{{ $item->id }}" {{ ($item->id == $data->role_id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label for="i_department">Department</label>
                                    <select class="form-control" name="department" id="i_department">
                                        <option value="">-- Pilih Department --</option>
                                        @foreach($department as $item)
                                            <option value="{{ $item->id }}" {{ ($item->id == $data->department_id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="iName">Name <span class="text-danger">*</span></label>
                            <input id="iName" name="name" type="text" class="form-control" value="{{ $data->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="iEmail">Email</label>
                            <input id="iEmail" name="email" type="email" class="form-control" value="{{ $data->email }}">
                        </div>
                        <div class="form-group">
                            <label for="iUsername">Username <span class="text-danger">*</span></label>
                            <input id="iUsername" name="username" type="text" class="form-control" value="{{ $data->username }}" required>
                        </div>
                        <div class="form-group">
                            <label for="iPassword">Password</label>
                            <input id="iPassword" name="password" type="password" class="form-control">
                            <small>Password lama akan digunakan jika password dikosongkan</small>
                        </div>
                    </div>
                    <div class="card-footer bg-gradient-secondary">
                        <div class="row justify-content-between">
                            <div class="col-sm-12 col-md-4 col-lg-2">
                                <a href="{{ route('admin.master-data.user-aplikasi.view.index') }}" class="btn btn-block btn-outline-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-2">
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
        const id = () => document.getElementById('id').value
        const role = () => document.getElementById('iRole').value
        const department = () => document.getElementById('i_department').value
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
                        id: id(),
                        department: department(),
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
