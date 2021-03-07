@extends('admin._layout')

@section('title','System Utility - Menu Group - Create')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">System Utility</li>
    <li class="breadcrumb-item">Menu Group</li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('style')
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>Create New Menu Group</strong>
                </div>
                <form id="formData">
                    <div class="card-body">
                        <input type="hidden" name="type" value="baru">
                        <div class="form-group">
                            <label for="iName">Name</label>
                            <input id="iName" name="name" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="iSegmentName">Segment Name</label>
                            <input id="iSegmentName" name="segment_name" type="text" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="iOrder">Order</label>
                                    <input id="iOrder" name="ord" type="text" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-8 col-lg-9">
                                <div class="form-group">
                                    <label for="iIcon">Icon</label>
                                    <input id="iIcon" name="icon" type="text" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-gradient-secondary">
                        <div class="row justify-content-between">
                            <div class="col-sm-12 col-md-4 col-lg-2">
                                <a href="{{ route('admin.system-utility.menu-group.view.index') }}" class="btn btn-block btn-outline-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
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
        const name = () => document.getElementById('iName').value
        const segment_name = () => document.getElementById('iSegmentName').value
        const icon = () => document.getElementById('iIcon').value
        const order = () => document.getElementById('iOrder').value

        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById('formData').addEventListener('submit', event => {
                event.preventDefault()

                axios({
                    url: '{{ route('admin.system-utility.menu-group.api.store') }}',
                    method: 'post',
                    data: {
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
