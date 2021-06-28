@extends('admin._layout')

@section('title','Purchasing - Purchase Order')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Purchasing</li>
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
                            <a class="btn btn-success btn-block btn-sm" href="{{ route('admin.purchasing.purchase-order.view.create') }}">Tambah PO</a>
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
        order: [
            [ 0, 'desc' ]
        ],
        ajax: {
            url: '{{ route('admin.purchasing.purchase-order.api.data') }}',
            method: 'post'
        },
        columns: [
            {data: 'invoice_number', name: 'purchase_order_infos.invoice_number', className: 'align-middle font-weight-bold'},
            {data: 'supplier_name', name: 'suppliers.name', className: 'align-middle'},
            {data: 'total_price', name: 'purchase_order_infos.total_price', className: 'align-middle'},
            {data: 'created_at', name: 'purchase_order_infos.created_at', className: 'align-middle'},
            {data: 'total_item', name: 'purchase_order_infos.total_item', width: '15%', className: 'align-middle'},
            {data: 'action', searchable: false, orderable: false, width: '5%', className: 'align-middle'},
        ],
        columnDefs: [
            {
                targets: 2,
                render: (data, type, row, meta) => {
                    return `<div class="d-flex justify-content-between"><div class="p-2 bd-highlight">Rp </div><div class="p-2 bd-highlight">${numeral(data).format('0,0')}</div></div>`
                }
            },
            {
                targets: 4,
                render: (data, type, row, meta) => {
                    let now = Math.round(row.received_item/data*100);
                    if (now < 100) {
                        return `<div class="progress"><div class="progress-bar progress-bar-striped bg-info" style="width: ${now}%" role="progressbar" aria-valuenow="${now}" aria-valuemin="0" aria-valuemax="${data}">${now}%</div></div>`;
                    } else {
                        return `<span class="badge badge-success">Completed</span>`;
                    }
                }
            },
        ],

    });

    document.addEventListener("DOMContentLoaded", () => {
        const t_list_tbody = $('#t_list tbody');
        const t_list_data = row => t_list.row( row ).data();

        t_list_tbody.on('click','button.action-info', function (event) {
            let data = t_list_data($(event.target).parents('tr'));
            window.location = `{{ url('admin/purchasing/purchase-order/info') }}/${data.id}`;
        });
    });
</script>
@endsection
