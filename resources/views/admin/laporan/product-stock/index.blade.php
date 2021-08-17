@extends('admin._layout')

@section('title','Laporan - Mutasi Stock')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Laporan</li>
    <li class="breadcrumb-item active">Product Stock</li>
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
{{--                <div class="card-body">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-sm-12 col-md-4 col-lg-4">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="filter_product">Produk</label>--}}
{{--                                <select class="form-control" id="filter_product" style="width: 100%"></select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="d-flex justify-content-end">--}}
{{--                        <button class="btn btn-outline-info" id="export_excel">Download Excel</button>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="card-body border-top">
                    <table id="t_list" class="table table-hover table-bordered" style="width: 100%">
                        <thead class="bg-dark">
                        <tr>
                            <th rowspan="2">Kode</th>
                            <th rowspan="2">Nama</th>
                            <th rowspan="2">Stok</th>
                            <th rowspan="2">Satuan</th>
                            <th colspan="3" class="text-center">Harga</th>
                            <th rowspan="2">Tgl Input</th>
                            <th rowspan="2">Update Terakhir</th>
                        </tr>
                        <tr>
                            <th>Supplier</th>
                            <th>Terakhir</th>
                            <th>Rata-rata</th>
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
            // deferLoading: 0,
            ajax: {
                url: '{{ route('admin.laporan.product-stock.datatable') }}',
                method: 'post',
                // data: function (d) {
                //     d.product_id = filter_product_val();
                //     d.start_date = filter_tanggal_val().start_date;
                //     d.end_date = filter_tanggal_val().end_date;
                // }
            },
            columns: [
                {data: 'code', name: 'products.code', width: '5%', className: 'align-middle font-weight-bold'},
                {data: 'name', name: 'products.name', className: 'align-middle'},
                {
                    data: 'stock', name: 'products.stock', className: 'align-middle text-right',
                    render: function (data) {
                        return new Intl.NumberFormat('id').format(data)
                    }
                },
                {data: 'satuan', name: 'satuans.nama AS satuan', className: 'align-middle'},
                {
                    data: 'supplier_price', name: 'products.supplier_price', className: 'align-middle text-right',
                    render: function (data) {
                        return new Intl.NumberFormat('id').format(data)
                    }
                },
                {
                    data: 'last_price', name: 'products.last_price', className: 'align-middle text-right',
                    render: function (data) {
                        return new Intl.NumberFormat('id').format(data)
                    }
                },
                {
                    data: 'avg_price', name: 'products.avg_price', className: 'align-middle text-right',
                    render: function (data) {
                        return new Intl.NumberFormat('id').format(data)
                    }
                },
                {
                    data: 'created_at', name: 'products.created_at', width: '5%', className: 'align-middle text-nowrap',
                    render: function (data) {
                        return moment(data).format('DD-MM-YYYY, HH:mm')
                    }
                },
                {
                    data: 'updated_at', name: 'products.updated_at', width: '5%', className: 'align-middle text-nowrap',
                    render: function (data) {
                        return moment(data).format('DD-MM-YYYY, HH:mm')
                    }
                },
            ],
            order: [[0,'asc']]
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
                let url = new URL('{{ route('admin.laporan.product-stock.export-excel') }}');
                window.open(url.href);
            });
        });
    </script>
@endsection
