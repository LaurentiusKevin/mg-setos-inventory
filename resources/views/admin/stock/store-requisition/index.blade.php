@extends('admin._layout')

@section('title','Stock - Store Requisition')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Stock</li>
    <li class="breadcrumb-item active">Store Requisition</li>
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
                            @if($role->create == 1)
                                <a class="btn btn-success btn-block btn-sm" href="{{ route('admin.stock.store-requisition.view.create') }}">
                                    <i class="fas fa-plus mr-2"></i> Create SR
                                </a>
                            @endif
                        </div>
                    </div>
                    <strong>History Store Requisition</strong>
                </div>
                <div class="card-body">
                    <table id="t_list" class="table table-hover table-bordered" style="width: 100%">
                        <thead class="bg-dark">
                        <tr>
                            <th>Kode</th>
                            <th>Department</th>
                            <th>Info Penggunaan</th>
                            <th>Catatan</th>
                            <th>Total Barang</th>
                            <th>Total Harga</th>
                            <th>Penginput</th>
                            <th>Tanggal Input</th>
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
        ajax: {
            url: '{{ route('admin.stock.store-requisition.api.data') }}',
            method: 'post'
        },
        columns: [
            {data: 'invoice_number', name: 'store_requisition_infos.invoice_number', width: '10%', className: 'align-middle font-weight-bold text-nowrap'},
            {data: 'department', name: 'departments.name', className: 'align-middle'},
            {data: 'info_penggunaan', name: 'store_requisition_infos.info_penggunaan', className: 'align-middle'},
            {data: 'catatan', name: 'store_requisition_infos.catatan', className: 'align-middle'},
            {data: 'total_item', name: 'store_requisition_infos.total_item', width: '5%', className: 'align-middle text-right'},
            {data: 'total_price', name: 'store_requisition_infos.total_price', width: '10%', className: 'align-middle text-right text-nowrap'},
            {data: 'penginput', name: 'users.name', width: '10%', className: 'align-middle'},
            {data: 'created_at', name: 'store_requisition_infos.created_at', width: '10%', className: 'align-middle text-nowrap'},
            {data: 'action', searchable: false, orderable: false, width: '5%', className: 'align-middle text-center'},
        ],
        columnDefs: [
            {
                targets: 4,
                render: (data, type, row, meta) => {
                    return numeral(data).format('0,0');
                }
            },
            {
                targets: 5,
                render: (data, type, row, meta) => {
                    return 'Rp '+numeral(data).format('0,0');
                }
            },
            {
                targets: 7,
                render: (data, type, row, meta) => {
                    return moment(data).format('DD-MM-YYYY, HH:mm:ss')
                }
            }
        ],
        order: [[7,'desc']]
    });

    document.addEventListener("DOMContentLoaded", () => {
        const t_list_tbody = $('#t_list tbody');
        const t_list_data = row => t_list.row( row ).data();

        t_list_tbody.on('click','button.action-verification', function (event) {
            let data = t_list_data($(event.target).parents('tr'));
            window.location = `{{ url('admin/stock/store-requisition/verification') }}/${data.id}`;
        });

        t_list_tbody.on('click','button.action-edit', function (event) {
            let data = t_list_data($(event.target).parents('tr'));
            window.location = `{{ url('admin/stock/store-requisition/edit') }}/${data.id}`;
        });

        t_list_tbody.on('click','button.action-info', function (event) {
            let data = t_list_data($(event.target).parents('tr'));
            window.location = `{{ url('admin/stock/store-requisition/info') }}/${data.id}`;
        });

        t_list_tbody.on('click','button.action-delete', event => {
            let data = t_list_data($(event.target).parents('tr'));

            Swal.fire({
                title: 'Hapus SR ini?',
                icon: 'warning',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonText: 'Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios({
                        url: '{{ route('admin.stock.store-requisition.api.delete') }}',
                        method: 'post',
                        data: {
                            store_requisition_info_id: data.id
                        }
                    }).then(response => {
                        if (response.data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus',
                                timer: 1200,
                                showConfirmButton: false,
                                willClose(popup) {
                                    t_list.ajax.reload(null,false);
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
