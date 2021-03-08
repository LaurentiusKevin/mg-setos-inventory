@extends('admin._layout')

@section('title','Master Data - User Role - Create')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item">User Role</li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('style')
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>Create New User Role</strong>
                </div>
                <form id="formData">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="iName">Nama</label>
                            <input type="text" class="form-control" name="name" id="iName" required>
                        </div>
                        <div class="form-group">
                            <label for="iInfo">Info</label>
                            <input type="text" class="form-control" name="info" id="iInfo">
                        </div>
                        <label for="t_i_menu">Menu</label>
                        <table class="table table-sm table-bordered table-hover" id="t_i_menu">
                            <thead>
                            <tr class="table-primary">
                                <th class="text-center">Nama Menu</th>
                                <th class="text-center" style="width: 10%">View</th>
                                <th class="text-center" style="width: 10%">Create</th>
                                <th class="text-center" style="width: 10%">Edit</th>
                                <th class="text-center" style="width: 10%">Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($menu AS $group)
                                <tr class="table-active">
                                    <th colspan="5">{{ $group['name'] }}</th>
                                </tr>
                                @foreach($group['menu'] AS $m)
                                    <tr>
                                        <th>&nbsp;&nbsp;{{ $m['name'] }}</th>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="view[]" value="{{ $m['id'] }}" id="view-{{ $m['id'] }}">
                                                <label class="form-check-label" for="view-{{ $m['id'] }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="create[]" value="{{ $m['id'] }}" id="create-{{ $m['id'] }}">
                                                <label class="form-check-label" for="create-{{ $m['id'] }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="edit[]" value="{{ $m['id'] }}" id="edit-{{ $m['id'] }}">
                                                <label class="form-check-label" for="edit-{{ $m['id'] }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="delete[]" value="{{ $m['id'] }}" id="delete-{{ $m['id'] }}">
                                                <label class="form-check-label" for="delete-{{ $m['id'] }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-gradient-secondary">
                        <div class="row justify-content-between">
                            <div class="col-sm-12 col-md-4 col-lg-2">
                                <a href="{{ route('admin.master-data.user-role.view.index') }}" class="btn btn-block btn-outline-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
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
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script type="text/javascript">
        const name = () => document.getElementById('iName').value
        const segment_name = () => document.getElementById('iSegmentName').value
        const icon = () => document.getElementById('iIcon').value
        const order = () => document.getElementById('iOrder').value

        document.addEventListener("DOMContentLoaded", () => {
            $('#formData').on('submit', function (event) {
                event.preventDefault()

                axios({
                    url: '{{ route('admin.master-data.user-role.api.store') }}',
                    method: 'post',
                    data: $(this).serialize()
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
                            title: 'Data Gagal Tersimpan'
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
