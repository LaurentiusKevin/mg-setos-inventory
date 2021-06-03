@extends('admin._layout')

@section('title','Master Data - SR Verificator')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">SR Verificator</li>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fancybox.css') }}">
    <style>
        .swal2-container {
            display: flex;
            position: fixed;
            z-index: 99999;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            padding: 0.625em;
            overflow-x: hidden;
            transition: background-color 0.1s;
            -webkit-overflow-scrolling: touch;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="card-header-actions">
                        <div class="card-header-actions">
                            <button type="button" class="btn btn-success btn-block btn-sm" id="add_verificator">Add Verificator</button>
                        </div>
                    </div>
                    <strong>Verificator</strong>
                </div>
                <div class="card-body">
                    <table id="t_list" class="table table-hover table-bordered" style="width: 100%">
                        <thead class="bg-dark">
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>e-Mail</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/datatables.js') }}"></script>
<script src="{{ asset('js/fancybox.js') }}"></script>
<script src="{{ asset('js/moment.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script type="text/javascript">
    const t_list = $('#t_list').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: {
            url: '{{ route('admin.master-data.sr-verificator.api.data') }}',
            method: 'post'
        },
        columns: [
            {data: 'name', name: 'users.name', className: 'align-middle'},
            {data: 'department', name: 'departments.name', className: 'align-middle'},
            {data: 'email', name: 'users.email', className: 'align-middle', width: '15%'},
            {data: 'status', name: 'store_requisition_verificators.primary', className: 'align-middle text-nowrap', width: '5%'},
            {data: 'action', searchable: false, orderable: false, className: 'text-center', width: '5%'},
        ]
    });

    document.addEventListener("DOMContentLoaded", () => {
        const t_list_tbody = $('#t_list tbody');
        const t_list_data = row => t_list.row( row ).data();

        document.getElementById('add_verificator').addEventListener('click', event => {
            Loader.button('#add_verificator', 'spinner-border spinner-border-sm mr-2');
            axios({
                url: '{{ route('admin.master-data.sr-verificator.view.create') }}',
                method: 'post'
            }).then(response => {
                Loader.button('#add_verificator', 'spinner-border spinner-border-sm mr-2');
                $.fancybox.open(response.data);
            })
        });

        t_list_tbody.on('click','button.action-edit', function (event) {
            let data = t_list_data($(event.target).parents('tr'));
            window.location = `{{ url('admin/master-data/department/edit') }}/${data.id}`;
        });

        t_list_tbody.on('click','button.action-primary', function (event) {
            let data = t_list_data($(event.target).parents('tr'));

            Swal.fire({
                title: 'Atur sebagai penanda tangan utama?',
                icon: 'warning',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonText: 'Atur Utama!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios({
                        url: '{{ route('admin.master-data.sr-verificator.api.set-primary') }}',
                        method: 'post',
                        data: {
                            id: data.id
                        }
                    }).then(response => {
                        if (response.data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Tersimpan!',
                                timer: 1200,
                                showConfirmButton: false,
                                willClose(popup) {
                                    t_list.ajax.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Gagal',
                                text: 'Silahkan coba lagi'
                            });
                        }
                    }).catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terdapat Kesalahan Pada System',
                            text: error.response.data.message
                        });
                    })
                }
            });
        });

        t_list_tbody.on('click','button.action-delete', event => {
            let data = t_list_data($(event.target).parents('tr'));

            Swal.fire({
                title: 'Hapus verificator?',
                icon: 'warning',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonText: 'Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios({
                        url: '{{ route('admin.master-data.sr-verificator.api.delete') }}',
                        method: 'post',
                        data: {
                            id: data.id
                        }
                    }).then(response => {
                        if (response.data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus',
                                timer: 1200,
                                showConfirmButton: false,
                                willClose(popup) {
                                    t_list.ajax.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Gagal',
                                text: 'Silahkan coba lagi'
                            });
                        }
                    }).catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terdapat Kesalahan Pada System',
                            text: error.response.data.message
                        });
                    })
                }
            });
        });
    });
</script>
@endsection
