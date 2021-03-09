@extends('admin._layout')

@section('title','Master Data - Satuan Produk')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">Satuan Produk</li>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/datatables.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="card-header-actions">
                        <div class="card-header-actions">
                            <a class="btn btn-success btn-block btn-sm" href="{{ route('admin.master-data.satuan-produk.view.create') }}">Tambah</a>
                        </div>
                    </div>
                    <strong>List Data Satuan</strong>
                </div>
                <div class="card-body">
                    <table id="t_list" class="table table-hover table-bordered" style="width: 100%">
                        <thead class="bg-dark">
                        <tr>
                            <th>Satuan</th>
                            <th>Ditambahkan Pada</th>
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
            url: '{{ route('admin.master-data.satuan-produk.api.data') }}',
            method: 'post'
        },
        columns: [
            {data: 'nama'},
            {data: 'created_at', width: '15%'},
            {data: 'action', width: '5%'},
        ],
        columnDefs: [
            {
                targets: 1,
                render: (data, type, row, meta) => {
                    return `<span class="text-nowrap">${moment(data).format('DD MMMM YYYY, HH:mm:ss')}</span>`;
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
            window.location = `{{ url('admin/master-data/satuan-produk/edit') }}/${data.id}`;
        });

        t_list_tbody.on('click','button.action-delete', event => {
            let data = t_list_data($(event.target).parents('tr'));

            Swal.fire({
                title: 'Hapus satuan ini?',
                icon: 'warning',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonText: 'Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios({
                        url: '{{ route('admin.master-data.satuan-produk.api.delete') }}',
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
