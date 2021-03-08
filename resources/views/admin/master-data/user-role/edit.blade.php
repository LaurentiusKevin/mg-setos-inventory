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
                    <input type="hidden" name="id" value="{{ $data->id }}">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="iName">Name</label>
                            <input id="iName" name="name" type="text" class="form-control" value="{{ $data->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="iInfo">Info</label>
                            <input type="text" class="form-control" name="info" id="iInfo" value="{{ $data->info }}">
                        </div>
                        <label>Menu</label>
                        <table class="table table-sm table-bordered table-hover">
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
                            @foreach($groups AS $group)
                                <tr class="table-active">
                                    <th colspan="5">{{ $group['name'] }}</th>
                                </tr>
                                @foreach($group['menu'] AS $m)
                                    <tr>
                                        <th>&nbsp;&nbsp;{{ $m['name'] }}</th>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="view[]" value="{{ $m['id'] }}" id="view-{{ $m['id'] }}" {{ ($m['view']) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="view-{{ $m['id'] }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="create[]" value="{{ $m['id'] }}" id="create-{{ $m['id'] }}" {{ ($m['create']) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="create-{{ $m['id'] }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="edit[]" value="{{ $m['id'] }}" id="edit-{{ $m['id'] }}" {{ ($m['edit']) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="edit-{{ $m['id'] }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="delete[]" value="{{ $m['id'] }}" id="delete-{{ $m['id'] }}" {{ ($m['delete']) ? 'checked' : '' }}>
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
                                <a href="{{ url()->previous() }}" class="btn btn-block btn-outline-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
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
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script type="text/javascript">
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
