@extends('admin._layout')

@section('title','Master Data - Product - Edit')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item">Product</li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('style')
    <link href="{{ asset('css/filepond.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fancybox.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>Edit Product</strong>
                </div>
                <form id="formData">
                    <div class="card-body">
                        <input type="hidden" id="sys_menu_group_id" value="{{ $data->id }}">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label for="i_code">Kode</label>
                                    <input id="i_code" name="code" type="text" class="form-control" value="{{ $data->code }}" required>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-8 col-lg-8">
                                <div class="form-group">
                                    <label for="i_name">Name</label>
                                    <input id="i_name" name="name" type="text" class="form-control" value="{{ $data->name }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="i_image">Saved Image</label>
                            <a href="{{ url("admin/stock/product-list/api/get-image/".encrypt($data->image)) }}" data-fancybox data-caption="{{ $data->name }}">
                                <img src="{{ url("admin/stock/product-list/api/get-image/".encrypt($data->image)) }}" alt="" style="width: 75px" />
                            </a>
                        </div>
                        <div class="form-group">
                            <label for="i_image">Image</label>
                            <input id="i_image" name="image" type="file">
                        </div>
                        <div class="form-group">
                            <label for="i_satuan">Satuan</label>
                            <select class="form-control" id="i_satuan" name="name" required>
                                <option value="">-- Pilih Satuan --</option>
                                @foreach($satuan AS $item)
                                    <option value="{{ $item->id }}" {{ ($data->satuan_id == $item->id) ? 'selected' : '' }}>{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="i_department">Department</label>
                            <select class="form-control" id="i_department" name="name">
                                <option value="">-- Pilih Department --</option>
                                @foreach($department AS $item)
                                    <option value="{{ $item->id }}" {{ ($data->department_id == $item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="i_supplier_price">Supplier Price</label>
                            <input id="i_supplier_price" name="name" type="text" class="form-control" value="{{ $data->supplier_price }}">
                        </div>
                    </div>
                    <div class="card-footer bg-gradient-secondary">
                        <div class="row justify-content-between">
                            <div class="col-sm-12 col-md-4 col-lg-2">
                                <a href="{{ route('admin.master-data.product-list.view.index') }}" class="btn btn-block btn-outline-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
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
    <script src="{{ asset('js/fancybox.js') }}"></script>
    <script src="{{ asset('js/filepond.js') }}"></script>
    <script type="text/javascript">
        const id = () => document.getElementById('sys_menu_group_id').value
        const code = () => document.getElementById('i_code').value
        const name = () => document.getElementById('i_name').value
        const satuan = () => document.getElementById('i_satuan').value
        const department = () => document.getElementById('i_department').value
        const supplier_price = () => document.getElementById('i_supplier_price').value
        const i_image = document.getElementById('i_image');

        let image_file_path = null;
        let image_upload_process = false;

        FilePond.registerPlugin(
            FilePondPluginFileValidateType,
            FilePondPluginImageResize,
            FilePondPluginImagePreview,
            FilePondPluginImageTransform
        );

        const pond = FilePond.create( i_image );
        FilePond.setOptions({
            acceptedFileTypes: ['image/png'],
            allowImageTransform: true,
            allowImageResize: true,
            imageResizeTargetWidth: 1200,
            imageResizeMode: 'contain',
            allowImagePreview: true,
            allowMultiple: false,
            server: {
                url: '{{ route('admin.master-data.product-list.api.upload-image') }}',
                process: {
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    onload: response => {
                        image_upload_process = false;
                        let data = JSON.parse(response);

                        image_file_path = data.file_path;
                    },
                    ondata: formData => {
                        image_upload_process = true;
                        return formData;
                    }
                }
            }
        })

        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById('formData').addEventListener('submit', event => {
                event.preventDefault()

                axios({
                    url: '{{ route('admin.master-data.product-list.api.store') }}',
                    method: 'post',
                    data: {
                        id: id(),
                        code: code(),
                        name: name(),
                        satuan_id: satuan(),
                        department_id: department(),
                        price: supplier_price(),
                        image: image_file_path
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
