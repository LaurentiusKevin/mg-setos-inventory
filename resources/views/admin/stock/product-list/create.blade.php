@extends('admin._layout')

@section('title','Stock - Product List - Create')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Stock</li>
    <li class="breadcrumb-item">Product List</li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('style')
    <link href="{{ asset('css/filepond.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>Create New Product</strong>
                </div>
                <form id="formData">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label for="i_code">Kode</label>
                                    <input id="i_code" name="code" type="text" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-8 col-lg-8">
                                <div class="form-group">
                                    <label for="i_name">Name</label>
                                    <input id="i_name" name="name" type="text" class="form-control" required>
                                </div>
                            </div>
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
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="i_department">Department</label>
                            <select class="form-control" id="i_department" name="name" required>
                                <option value="">-- Pilih Department --</option>
                                @foreach($department AS $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="i_price">Price</label>
                            <input id="i_price" name="price" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="card-footer bg-gradient-secondary">
                        <div class="row justify-content-between">
                            <div class="col-sm-12 col-md-4 col-lg-2">
                                <a href="{{ route('admin.stock.product-list.view.index') }}" class="btn btn-block btn-outline-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
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
    <script src="{{ asset('js/filepond.js') }}"></script>
    <script type="text/javascript">
        const code = () => document.getElementById('i_code').value
        const name = () => document.getElementById('i_name').value
        const satuan = () => document.getElementById('i_satuan').value
        const department = () => document.getElementById('i_department').value
        const price = () => document.getElementById('i_price').value
        const i_image = document.getElementById('i_image');

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
                url: '{{ route('admin.stock.product-list.api.upload-image') }}',
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
                        url: '{{ route('admin.stock.product-list.api.store') }}',
                        method: 'post',
                        data: {
                            code: code(),
                            name: name(),
                            satuan_id: satuan(),
                            department_id: department(),
                            price: price(),
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
                }
            });
        });
    </script>
@endsection
