@extends('admin._layout')

@section('title','Purchasing - Receiving Order')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Purchasing</li>
    <li class="breadcrumb-item active">Receiving Order</li>
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
                            <a class="btn btn-success btn-block btn-sm" href="{{ route('admin.stock.receiving-order.view.create') }}">Receiving</a>
                        </div>
                    </div>
                    <strong>History Receiving</strong>
                </div>
                <div class="card-body">
                    <table id="t_list" class="table table-hover table-bordered" style="width: 100%">
                        <thead class="bg-dark">
                        <tr>
                            <th>Invoice</th>
                            <th>Supplier</th>
                            <th>Total</th>
                            <th>Dibuat Oleh</th>
                            <th>Tanggal Dibuat</th>
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
            url: '{{ route('admin.stock.receiving-order.api.data') }}',
            method: 'post'
        },
        columns: [
            {data: 'invoice_number', name: 'receiving_order_infos.invoice_number', className: 'font-weight-bold align-middle'},
            {data: 'supplier_name', name: 'suppliers.name', className: 'align-middle'},
            {data: 'total_price', name: 'receiving_order_infos.total_price', className: 'align-middle'},
            {data: 'penginput', name: 'users.name', className: 'align-middle'},
            {data: 'created_at', name: 'receiving_order_infos.created_at', className: 'align-middle'},
            {data: 'action', searchable: false, orderable: false, width: '5%', className: 'align-middle'},
        ],
        columnDefs: [
            {
                targets: 2,
                render: (data, type, row, meta) => {
                    return "Rp "+numeral(data).format('0,0');
                }
            }
        ],

    });

    document.addEventListener("DOMContentLoaded", () => {
        const t_list_tbody = $('#t_list tbody');
        const t_list_data = row => t_list.row( row ).data();

        t_list_tbody.on('click','button.action-info', function (event) {
            let data = t_list_data($(event.target).parents('tr'));
            window.location = `{{ url('admin/stock/receiving-order/info') }}/${data.id}`;
        });
    });
</script>
@endsection
