@extends('admin._layout')

@section('title','Master Data - Satuan Produk - Edit')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item">Satuan Produk</li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('style')
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>Edit Group Satuan</strong>
                </div>
                <form id="formData">
                    <div class="card-body">
                        <input type="hidden" id="sys_menu_group_id" value="{{ $data->id }}">
                        <div class="form-group">
                            <label for="iName">Name</label>
                            <input id="iName" name="name" type="text" class="form-control" value="{{ $data->nama }}" required>
                        </div>
                    </div>
                    <div class="card-footer bg-gradient-secondary">
                        <div class="row justify-content-between">
                            <div class="col-sm-12 col-md-4 col-lg-2">
                                <a href="{{ route('admin.master-data.satuan-produk.view.index') }}" class="btn btn-block btn-outline-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
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
        const id = () => document.getElementById('sys_menu_group_id').value
        const nama = () => document.getElementById('iName').value

        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById('formData').addEventListener('submit', event => {
                event.preventDefault()

                axios({
                    url: '{{ route('admin.master-data.satuan-produk.api.store') }}',
                    method: 'post',
                    data: {
                        id: id(),
                        nama: nama()
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
