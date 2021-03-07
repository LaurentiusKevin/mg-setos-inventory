@extends('admin._layout')

@section('title','System Utility - Menu - Edit')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">System Utility</li>
    <li class="breadcrumb-item active">Menu</li>
@endsection

@section('style')
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>Edit Menu</strong>
                </div>
                <form id="formData">
                    <div class="card-body">
                        <input type="hidden" id="sys_menu_group_id" value="{{ $data->id }}">
                        <div class="form-group">
                            <label for="iGroup">Group Menu</label>
                            <select class="form-control" name="group_id" id="iGroup" required>
                                @foreach($group as $item)
                                    <option value="{{ $item->id }}" {{ ($data->sys_menu_group_id == $item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="iName">Name</label>
                            <input id="iName" name="name" type="text" class="form-control" value="{{ $data->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="iSegmentName">Segment Name</label>
                            <input id="iSegmentName" name="segment_name" type="text" class="form-control" value="{{ $data->segment_name }}" required>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="iOrder">Order</label>
                                    <input id="iOrder" name="ord" type="text" class="form-control" value="{{ $data->ord }}" required>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-8 col-lg-9">
                                <div class="form-group">
                                    <label for="iUrl">Url</label>
                                    <input type="text" class="form-control" name="url" id="iUrl" value="{{ $data->url }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-gradient-secondary">
                        <div class="row justify-content-between">
                            <div class="col-sm-12 col-md-4 col-lg-2">
                                <a href="{{ route('admin.system-utility.menu.view.index') }}" class="btn btn-block btn-outline-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
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
        const id = () => document.getElementById('sys_menu_group_id').value
        const group = () => document.getElementById('iGroup').value
        const name = () => document.getElementById('iName').value
        const segment_name = () => document.getElementById('iSegmentName').value
        const order = () => document.getElementById('iOrder').value
        const url = () => document.getElementById('iUrl').value

        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById('formData').addEventListener('submit', event => {
                event.preventDefault()

                axios({
                    url: '{{ route('admin.system-utility.menu.api.store') }}',
                    method: 'post',
                    data: {
                        id: id(),
                        group_menu: group(),
                        name: name(),
                        segment_name: segment_name(),
                        order: order(),
                        url: url()
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
