@extends('admin._layout')

@section('title','Laporan - Invoicing Per Department')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Laporan</li>
    <li class="breadcrumb-item active">Invoicing Per Department</li>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}">
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
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="filter_department">Department</label>
                                <select class="form-control" id="filter_department">
                                    @foreach($department AS $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="filter_tanggal">Tanggal Selesai</label>
                                <input class="form-control" id="filter_tanggal">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <table id="t_list" class="table table-hover table-bordered" style="width: 100%">
                        <thead class="bg-dark">
                        <tr>
                            <th>No Invoice</th>
                            <th>Department</th>
                            <th>Info Penggunaan</th>
                            <th>Total Harga</th>
                            <th>Tanggal Selesai</th>
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
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script type="text/javascript">
        let filter_department = document.getElementById('filter_department');
        let filter_department_val = () => filter_department.value;

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
            ajax: {
                url: '{{ route('admin.laporan.invoicing-per-department.datatable') }}',
                method: 'post',
                data: function (d) {
                    d.department_id = filter_department_val();
                    d.start_date = filter_tanggal_val().start_date;
                    d.end_date = filter_tanggal_val().end_date;
                }
            },
            columns: [
                {data: 'invoice_number', name: 'ii.invoice_number', width: '5%', className: 'font-weight-bold align-middle'},
                {data: 'department_name', name: 'd.name', className: 'align-middle'},
                {data: 'info_penggunaan', name: 'ii.info_penggunaan', className: 'align-middle'},
                {
                    data: 'total_price', sortable: false, width: '5%', className: 'text-right align-middle text-nowrap',
                    render: function (data) {
                        let formatted = new Intl.NumberFormat('id').format(data);
                        return `<div class="d-flex justify-content-between"><span>Rp</span><span>${formatted}</span></div>`
                    }
                },
                {data: 'completed_at', name: 'ii.completed_at', width: '5%', className: 'text-center align-middle text-nowrap'},
            ],
        });

        document.addEventListener("DOMContentLoaded", () => {
            filter_department.addEventListener('change', event => {
                event.preventDefault();
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
