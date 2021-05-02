@extends('admin._layout')

@section('title','Purchasing - Invoicing')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Purchasing</li>
    <li class="breadcrumb-item active">Invoicing</li>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fancybox.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-2 col-lg-2">
            <div class="form-group">
                <label for="f-status-selesai">Status</label>
                <select class="form-control" id="f-status-selesai">
                    <option value="0">Belum selesai</option>
                    <option value="1">Sudah selesai</option>
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
{{--                    <div class="card-header-actions">--}}
{{--                        <div class="card-header-actions">--}}
{{--                            <a class="btn btn-info btn-block btn-sm" href="{{ route('admin.purchasing.invoicing.view.history') }}">--}}
{{--                                <i class="fas fa-history mr-2"></i> History--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <strong>Daftar SR</strong>
                </div>
                <div class="card-body">
                    <table id="t_list" class="table table-hover table-bordered" style="width: 100%">
                        <thead class="bg-dark">
                        <tr>
                            <th>No SR</th>
                            <th>Info Penggunaan</th>
                            <th>Catatan</th>
                            <th>Departemen</th>
                            <th>Total Barang</th>
                            <th>Tgl Verifikasi</th>
                            <th>Penginput SR</th>
                            <th><i class="fas fa-ellipsis-h"></i></th>
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
    let f_status_selesai = document.getElementById('f-status-selesai');
    let status_selesai = () => parseInt(f_status_selesai.value);

    const t_list = $('#t_list').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: {
            url: '{{ route('admin.purchasing.invoicing.api.data') }}',
            method: 'post',
            data: function (d) {
                d.f_status_selesai = status_selesai()
            }
        },
        columns: [
            {data: 'invoice_number_sr', name: 'sri.invoice_number', className: 'align-middle font-weight-bold'},
            {data: 'info_penggunaan', name: 'ii.info_penggunaan', className: 'align-middle'},
            {data: 'catatan', name: 'ii.catatan', className: 'align-middle'},
            {data: 'department_name', name: 'd.name', className: 'align-middle'},
            {data: 'total_item', name: 'ii.total_item', className: 'align-middle'},
            {data: 'verified_at', name: 'sri.verified_at', className: 'align-middle'},
            {data: 'penginput_sr', name: 'u2.name', width: '15%', className: 'align-middle'},
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
                    return moment(data).format('DD-MM-YYYY, HH:mm:ss')
                }
            }
        ],
    });

    document.addEventListener("DOMContentLoaded", () => {
        f_status_selesai.addEventListener('change', event => {
            t_list.ajax.reload();
        });

        const t_list_tbody = $('#t_list tbody');
        const t_list_data = row => t_list.row( row ).data();

        t_list_tbody.on('click','button.action-process', function (event) {
            let data = t_list_data($(event.target).parents('tr'));
            window.location = `{{ url('admin/purchasing/invoicing/process') }}/${data.id}`;
        });

        t_list_tbody.on('click','button.action-detail', function (event) {
            let data = t_list_data($(event.target).parents('tr'));
            window.location = `{{ url('admin/purchasing/invoicing/detail') }}/${data.id}`;
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
                        url: '{{ route('admin.purchasing.invoicing.api.delete') }}',
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
