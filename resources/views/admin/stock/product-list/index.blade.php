@extends('admin._layout')

@section('title','Stock - Product List')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Stock</li>
    <li class="breadcrumb-item active">Product List</li>
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
                            <a class="btn btn-success btn-block btn-sm" href="{{ route('admin.stock.product-list.view.create') }}">Tambah</a>
                        </div>
                    </div>
                    <strong>List Data Product</strong>
                </div>
                <div class="card-body">
                    <table id="t_list" class="table table-hover table-bordered" style="width: 100%">
                        <thead class="bg-dark">
                        <tr>
                            <th>Product</th>
                            <th>Stock</th>
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
            url: '{{ route('admin.stock.product-list.api.data') }}',
            method: 'post'
        },
        columns: [
            {data: 'image', width: '20%'},
            {data: 'stock', className: "align-middle"},
            {data: 'last_price', className: "align-middle"},
            {data: 'avg_price', className: "align-middle"},
            {data: 'action', width: '5%', className: "align-middle"},
        ],
        columnDefs: [
            {
                targets: 0,
                render: (data, type, row, meta) => {
                    return `
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50 flex-shrink-0">
                            <a href="${data}" data-fancybox data-caption="${row.name}">
                                <img src="${data}" alt="" style="width: 75px" />
                            </a>
                        </div>
                        <div class="ml-3">
                            <span class="text-dark-75 font-weight-bold line-height-sm d-block pb-2">${row.name}</span>
                        </div>
                    </div>`;
                }
            },
            {
                targets: 1,
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
            window.location = `{{ url('admin/stock/product-list/edit') }}/${data.id}`;
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
                        url: '{{ route('admin.stock.product-list.api.delete') }}',
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
