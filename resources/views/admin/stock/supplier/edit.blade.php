@extends('admin._layout')

@section('title','Stock - Supplier - Edit')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Stock</li>
    <li class="breadcrumb-item">Supplier</li>
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
                    <strong>Edit Supplier</strong>
                </div>
                <form id="formData">
                    <div class="card-body">
                        <input type="hidden" id="sys_menu_group_id" value="{{ $data->id }}">
                        <div class="form-group">
                            <label for="i_name">Name</label>
                            <input id="i_name" name="name" type="text" class="form-control" value="{{ $data->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="i_image">Saved Logo</label>
                            <a href="{{ url("admin/stock/supplier/api/get-image/".encrypt($data->logo)) }}" data-fancybox data-caption="{{ $data->name }}">
                                <img src="{{ url("admin/stock/supplier/api/get-image/".encrypt($data->logo)) }}" alt="" style="width: 75px" />
                            </a>
                        </div>
                        <div class="form-group">
                            <label for="i_logo">Logo</label>
                            <input id="i_logo" name="logo" type="file">
                        </div>
                        <div class="form-group">
                            <label for="i_phone">Telp</label>
                            <input id="i_phone" name="phone" type="text" class="form-control" value="{{ $data->phone }}">
                        </div>
                        <div class="form-group">
                            <label for="i_address">Alamat</label>
                            <textarea id="i_address" name="address" class="form-control">{{ $data->address }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="i_contact_person_name">Nama Contact Person</label>
                            <input id="i_contact_person_name" name="contact_person_name" type="text" class="form-control" value="{{ $data->contact_person_name }}">
                        </div>
                        <div class="form-group">
                            <label for="i_contact_person_phone">Telp Contact Person</label>
                            <input id="i_contact_person_phone" name="contact_person_phone" type="text" class="form-control" value="{{ $data->contact_person_phone }}">
                        </div>
                        <div class="form-group">
                            <label for="i_info">Info</label>
                            <textarea id="i_info" name="info" class="form-control">{{ $data->info }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer bg-gradient-secondary">
                        <div class="row justify-content-between">
                            <div class="col-sm-12 col-md-4 col-lg-2">
                                <a href="{{ route('admin.stock.supplier.view.index') }}" class="btn btn-block btn-outline-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
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
        const name = () => document.getElementById('i_name').value
        const phone = () => document.getElementById('i_phone').value
        const address = () => document.getElementById('i_address').value
        const contact_person_name = () => document.getElementById('i_contact_person_name').value
        const contact_person_phone = () => document.getElementById('i_contact_person_phone').value
        const info = () => document.getElementById('i_info').value
        const i_image = document.getElementById('i_logo');

        let image_file_path = null;
        let image_upload_process = false;

        FilePond.registerPlugin(FilePondPluginFileValidateType);
        FilePond.registerPlugin(FilePondPluginImageResize);
        FilePond.registerPlugin(FilePondPluginImagePreview);
        FilePond.registerPlugin(FilePondPluginImageTransform);

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
                url: '{{ route('admin.stock.supplier.api.upload-image') }}',
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

                if (image_upload_process === true) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Sedang dalam proses upload',
                        text: 'Silahkan tunggu hingga proses upload selesai'
                    })
                } else {
                    axios({
                        url: '{{ route('admin.stock.supplier.api.store') }}',
                        method: 'post',
                        data: {
                            id: id(),
                            name: name(),
                            phone: phone(),
                            address: address(),
                            contact_person_name: contact_person_name(),
                            contact_person_phone: contact_person_phone(),
                            info: info(),
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
                }
            });
        });
    </script>
@endsection
