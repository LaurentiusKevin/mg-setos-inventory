@extends('admin._layout')

@section('title','Master Data - Supplier')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">Supplier</li>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fancybox.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="card-header-actions">
                        <div class="card-header-actions">
                            <a class="btn btn-success btn-block btn-sm" href="{{ route('admin.master-data.supplier.view.create') }}">Tambah</a>
                        </div>
                    </div>
                    <strong>List Data Supplier</strong>
                </div>
                <div class="card-body">
                    <table id="t_list" class="table table-hover table-bordered" style="width: 100%">
                        <thead class="bg-dark">
                        <tr>
                            <th>Supplier</th>
                            <th>Alamat</th>
                            <th>Contact Person</th>
                            <th>Info</th>
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
<script src="{{ asset('js/moment.js') }}"></script>
<script src="{{ asset('js/fancybox.js') }}"></script>
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
            url: '{{ route('admin.master-data.supplier.api.data') }}',
            method: 'post'
        },
        columns: [
            {data: 'logo', name: 'name', width: '20%'},
            {data: 'address', name: 'address', className: "align-middle"},
            {data: 'contact_person_name', name: 'contact_person_name', width: '20%', className: "align-middle"},
            {data: 'info', name: 'info', className: "align-middle"},
            {data: 'action', searchable: false, orderable: false, width: '5%', className: "align-middle"},
        ],
        columnDefs: [
            {
                targets: 0,
                render: (data, type, row, meta) => {
                    return `
                    <a href="${data}" data-fancybox data-caption="${row.name}">
                        <span class="text-dark-75 font-weight-bold line-height-sm d-block pb-2">${row.name}</span>
                    </a>
                    <i class="fas fa-phone text-secondary mr-2"></i><span>${row.phone}</span>`;
                }
            },
            {
                targets: 2,
                render: (data, type, row, meta) => {
                    let cp = (data === null) ? '-' : data;
                    let phone = (row.contact_person_phone === null) ? '-' : row.contact_person_phone;
                    return `
                    <span class="text-dark-75 font-weight-bold line-height-sm d-block pb-2">${cp}</span>
                    <i class="fas fa-phone text-secondary mr-2"></i><span>${phone}</span>`;
                }
            },
        ],

    });

    document.addEventListener("DOMContentLoaded", () => {
        const t_list_tbody = $('#t_list tbody');
        const t_list_data = row => t_list.row( row ).data();

        t_list_tbody.on('click','button.action-edit', function (event) {
            console.log('coba')
            let data = t_list_data($(event.target).parents('tr'));
            window.location = `{{ url('admin/master-data/supplier/edit') }}/${data.id}`;
        });

        t_list_tbody.on('click','button.action-delete', event => {
            let data = t_list_data($(event.target).parents('tr'));

            Swal.fire({
                title: 'Hapus supplier ini?',
                icon: 'warning',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonText: 'Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios({
                        url: '{{ route('admin.master-data.supplier.api.delete') }}',
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
