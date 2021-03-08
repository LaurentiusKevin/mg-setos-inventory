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
                        <input type="hidden" id="sys_menu_group_id" value="{{ $data->id }}">
                        <div class="form-group">
                            <label for="iName">Name</label>
                            <input id="iName" name="name" type="text" class="form-control" value="{{ $data->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="iSegmentName">Segment Name</label>
                            <input id="iSegmentName" name="segment_name" type="text" class="form-control" value="{{ $data->segment_name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="iIcon">Icon</label>
                            <input id="iIcon" name="icon" type="text" class="form-control" value="{{ $data->icon }}" required>
                        </div>
                        <div class="form-group">
                            <label for="iOrder">Order</label>
                            <input id="iOrder" name="ord" type="text" class="form-control" value="{{ $data->ord }}" required>
                        </div>
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
    <script type="text/javascript">
        const id = () => document.getElementById('sys_menu_group_id').value
        const name = () => document.getElementById('iName').value
        const segment_name = () => document.getElementById('iSegmentName').value
        const icon = () => document.getElementById('iIcon').value
        const order = () => document.getElementById('iOrder').value

        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById('formData').addEventListener('submit', event => {
                event.preventDefault()

                axios({
                    url: '{{ route('admin.system-utility.menu-group.api.store-edit') }}',
                    method: 'post',
                    data: {
                        id: id(),
                        name: name(),
                        segment_name: segment_name(),
                        icon: icon(),
                        order: order()
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
