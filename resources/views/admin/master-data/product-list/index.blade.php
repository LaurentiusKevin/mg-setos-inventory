@extends('admin._layout')

@section('title','Master Data - Product')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">Product</li>
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
                            <a class="btn btn-success btn-block btn-sm" href="{{ route('admin.master-data.product-list.view.create') }}">Tambah</a>
                        </div>
                    </div>
                    <strong>List Data Product</strong>
                </div>
                <div class="card-body">
                    <table id="t_list" class="table table-hover table-bordered" style="width: 100%">
                        <thead class="bg-dark">
                        <tr>
                            <th>Code</th>
                            <th>Product</th>
                            <th>Department</th>
                            <th>Stock</th>
                            <th>Supplier Price</th>
                            <th>Last Price</th>
                            <th>Average Price</th>
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
            url: '{{ route('admin.master-data.product-list.api.data') }}',
            method: 'post'
        },
        columns: [
            // {data: 'image', width: '20%'},
            {data: 'code', name: 'code', className: "text-nowrap font-weight-bold align-middle", width: '5%'},
            {data: 'name', name: 'name', className: "align-middle", width: '20%'},
            {data: 'department', name: 'departments.name', className: "align-middle", width: '20%'},
            {data: 'stock', name: 'stock', className: "align-middle text-right"},
            {data: 'supplier_price', name: 'supplier_price', className: "align-middle text-right"},
            {data: 'last_price', name: 'last_price', className: "align-middle text-right"},
            {data: 'avg_price', name: 'avg_price', className: "align-middle text-right"},
            {data: 'action', sorting: 'false', searchable: 'false', width: '5%', className: "align-middle"},
        ],
        columnDefs: [
            // {
            //     targets: 0,
            //     render: (data, type, row, meta) => {
            //         return `
            //         <div class="d-flex align-items-center">
            //             <div class="symbol symbol-50 flex-shrink-0">
            //                 <a href="${data}" data-fancybox data-caption="${row.name}">
            //                     <img src="${data}" alt="" style="width: 75px" />
            //                 </a>
            //             </div>
            //             <div class="ml-3">
            //                 <span class="text-dark-75 font-weight-bold line-height-sm d-block pb-2">${row.name}</span>
            //             </div>
            //         </div>`;
            //     }
            // },
            {
                targets: 3,
                render: (data, type, row, meta) => {
                    return `<span class="text-nowrap">${data} ${row.satuan}</span>`;
                }
            },
        ]
    });

    document.addEventListener("DOMContentLoaded", () => {
        const t_list_tbody = $('#t_list tbody');
        const t_list_data = row => t_list.row( row ).data();

        t_list_tbody.on('click','button.action-edit', function (event) {
            console.log('coba')
            let data = t_list_data($(event.target).parents('tr'));
            window.location = `{{ url('admin/master-data/product-list/edit') }}/${data.id}`;
        });

        t_list_tbody.on('click','button.action-delete', event => {
            let data = t_list_data($(event.target).parents('tr'));

            Swal.fire({
                title: 'Hapus produk ini?',
                icon: 'warning',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonText: 'Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios({
                        url: '{{ route('admin.master-data.product-list.api.delete') }}',
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
