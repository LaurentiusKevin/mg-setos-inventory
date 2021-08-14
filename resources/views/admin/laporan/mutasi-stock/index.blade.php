@extends('admin._layout')

@section('title','Laporan - Mutasi Stock')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Laporan</li>
    <li class="breadcrumb-item active">Mutasi Stock</li>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <strong>Laporan</strong>
                        <div>
                            <button class="btn btn-outline-info btn-sm" id="export_excel">Download Excel</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label for="filter_product">Produk</label>
                                <select class="form-control" id="filter_product" style="width: 100%"></select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="filter_tanggal">Range Tanggal</label>
                                <input class="form-control" id="filter_tanggal">
                            </div>
                        </div>
                    </div>
{{--                    <div class="d-flex justify-content-end">--}}
{{--                        <button class="btn btn-outline-info" id="export_excel">Download Excel</button>--}}
{{--                    </div>--}}
                </div>
                <div class="card-body border-top">
                    <table id="t_list" class="table table-hover table-bordered" style="width: 100%">
                        <thead class="bg-dark">
                        <tr>
                            <th>No</th>
                            <th>Penginput</th>
                            <th>Tipe</th>
                            <th>Harga Satuan</th>
                            <th>IN</th>
                            <th>OUT</th>
                            <th>Stok</th>
                            <th>Tgl Input</th>
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
    <script src="{{ asset('js/daterangepicker.js') }}"></script>
    <script src="{{ asset('js/select2.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script type="text/javascript">
        let filter_product = $('#filter_product').select2({
            theme: 'bootstrap4',
            ajax: {
                url: '{{ route('admin.laporan.mutasi-stock.product-list') }}',
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function (params) {
                    // Query parameters will be ?search=[term]&page=[page]
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                }
            }
        });
        let filter_product_val = () => filter_product.val();

        let filter_tanggal = $('#filter_tanggal');
        filter_tanggal.daterangepicker({
            startDate: moment().startOf('month'),
            endDate: moment().endOf('month'),
            locale: {
                format: 'DD/MM/YYYY'
            }
        })
        let filter_tanggal_val = () => {
            return {
                start_date: filter_tanggal.data('daterangepicker').startDate.format('YYYY-MM-DD'),
                end_date: filter_tanggal.data('daterangepicker').endDate.format('YYYY-MM-DD')
            }
        }

        let export_excel = document.getElementById('export_excel');

        const t_list = $('#t_list').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            deferLoading: 0,
            ajax: {
                url: '{{ route('admin.laporan.mutasi-stock.datatable') }}',
                method: 'post',
                data: function (d) {
                    d.product_id = filter_product_val();
                    d.start_date = filter_tanggal_val().start_date;
                    d.end_date = filter_tanggal_val().end_date;
                }
            },
            columns: [
                {data: 'invoice_number', name: 'invoice_number', width: '5%', className: 'align-middle font-weight-bold'},
                {data: 'penginput', name: 'users.name', className: 'align-middle'},
                {
                    data: 'type', name: 'type', width: '5%', className: 'align-middle',
                    render: function (data) {
                        if (data === 1) {
                            return 'Receiving'
                        } else {
                            return 'Invoicing'
                        }
                    }
                },
                {
                    data: 'price', name: 'price', className: 'align-middle text-right',
                    render: function (data) {
                        return new Intl.NumberFormat('id').format(data);
                    }
                },
                {data: 'in', name: 'in', width: '5%', className: 'align-middle text-right'},
                {data: 'out', name: 'out', width: '5%', className: 'align-middle text-right'},
                {data: 'saldo', name: 'saldo', width: '5%', className: 'align-middle text-right'},
                {
                    data: 'created_at', name: 'product_transactions.created_at', className: 'align-middle',
                    render: function (data) {
                        let date = new Date(data);
                        let day = String(date.getDay()).padStart(2,'0');
                        let month = String(date.getMonth()).padStart(2,'0');
                        let year = String(date.getFullYear()).padStart(4,'0');
                        let hour = String(date.getHours()).padStart(2,'0');
                        let minute = String(date.getMinutes()).padStart(2,'0');
                        let second = String(date.getSeconds()).padStart(2,'0');
                        return `${day}-${month}-${year}, ${hour}:${minute}:${second}`
                    }
                },
            ],
            order: [[7,'asc']]
        });

        document.addEventListener("DOMContentLoaded", () => {
            filter_product.on('change', function (e) {
                t_list.ajax.reload();
            });

            filter_tanggal.on('apply.daterangepicker', function(ev, picker) {
                t_list.ajax.reload()
            });

            export_excel.addEventListener('click', event => {
                event.preventDefault();
                let url = new URL('{{ route('admin.laporan.invoicing-per-department.export-excel') }}');

                let date_filter = filter_tanggal_val();
                url.searchParams.append('start_date',date_filter.start_date);
                url.searchParams.append('end_date',date_filter.end_date);

                window.open(url.href);
            });
        });
    </script>
@endsection
