@extends('admin._layout')

@section('title','Stock - Purchase Order')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Stock</li>
    <li class="breadcrumb-item active">Purchase Order</li>
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
                            <a class="btn btn-success btn-block btn-sm" href="{{ route('admin.stock.purchase-order.view.create') }}">Tambah PO</a>
                        </div>
                    </div>
                    <strong>History Purchase Order</strong>
                </div>
                <div class="card-body">
                    <table id="t_list" class="table table-hover table-bordered" style="width: 100%">
                        <thead class="bg-dark">
                        <tr>
                            <th>Invoice</th>
                            <th>Supplier</th>
                            <th>Total</th>
                            <th>Tanggal Dibuat</th>
                            <th>Proses PO</th>
                            <th>#</th>
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
<script src="{{ asset('js/numeral.js') }}"></script>
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
        ordering: false,
        ajax: {
            url: '{{ route('admin.stock.purchase-order.api.data') }}',
            method: 'post'
        },
        columns: [
            {data: 'invoice_number', className: 'align-middle'},
            {data: 'supplier', className: 'align-middle'},
            {data: 'total_price', className: 'align-middle'},
            {data: 'created_at', className: 'align-middle'},
            {data: 'products_sum_quantity', width: '15%', className: 'align-middle'},
            {data: 'action', width: '5%', className: 'align-middle'},
        ],
        columnDefs: [
            {
                targets: 1,
                render: (data, type, row, meta) => {
                    return data.name;
                }
            },
            {
                targets: 2,
                render: (data, type, row, meta) => {
                    return "Rp "+numeral(data).format('0,0');
                }
            },
            {
                targets: 4,
                render: (data, type, row, meta) => {
                    let now = 2/data*100;
                    return `<div class="progress"><div class="progress-bar progress-bar-striped bg-success" style="width: ${now}%" role="progressbar" aria-valuenow="${now}" aria-valuemin="0" aria-valuemax="${data}">${now}%</div></div>`;
                }
            },
        ],

    });

    document.addEventListener("DOMContentLoaded", () => {
        const t_list_tbody = $('#t_list tbody');
        const t_list_data = row => t_list.row( row ).data();

        t_list_tbody.on('click','button.action-info', function (event) {
            let data = t_list_data($(event.target).parents('tr'));
            window.location = `{{ url('admin/stock/purchase-order/info') }}/${data.id}`;
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
                        url: '{{ route('admin.stock.supplier.api.delete') }}',
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
