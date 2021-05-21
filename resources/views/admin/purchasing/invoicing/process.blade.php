@extends('admin._layout')

@section('title','Purchasing - Invoicing - Process')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Purchasing</li>
    <li class="breadcrumb-item">Invoicing</li>
    <li class="breadcrumb-item active">Process</li>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/datatables.css') }}">
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endsection

@section('content')
    <form id="form-data">
        <input type="hidden" id="invoicing_info_id" value="{{ $invoicing_info_id }}">
        <input type="hidden" id="store_requisition_info_id" value="{{ $info_sr->id }}">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <strong>Info Store Requisition</strong>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-2">Info Penggunaan</dt>
                            <dd class="col-sm-10">{{ $info_sr->info_penggunaan }}</dd>

                            <dt class="col-sm-2">Catatan Tambahan</dt>
                            <dd class="col-sm-10">{{ $info_sr->catatan ?? '-' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <strong>Penanda Tangan Store Requisition</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="card card-accent-success">
                                    <div class="card-body">
                                        <h5 class="mb-1">{{ ucfirst($info_sr->penginput) }}</h5>
                                        <p class="mb-1">Tertanda Tangan Secara Digital</p>
                                        <p class="mb-1 font-italic">Tanggal: {{ date('d-m-Y, H:i:s',strtotime($info_sr->updated_at)) }}</p>
                                    </div>
                                </div>
                            </div>

                            @foreach($verificator AS $item)
                                <div class="col">
                                    <div class="card {{ ($item->verified_at == null) ? 'card-accent-warning' : 'card-accent-success' }}">
                                        <div class="card-body">
                                            <h5 class="mb-1">{{ ucfirst($item->verificator) }}</h5>
                                            <p class="mb-1">{{ ($item->verified_at == null) ? ' ' : 'Tertanda Tangan Secara Digital' }}</p>
                                            <p class="mb-1 font-italic">{{ ($item->verified_at == null) ? ' ' : 'Tanggal: '.date('d-m-Y, H:i:s',strtotime($item->verified_at)) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <strong>Products</strong>
                    </div>
                    <div class="card-body">
                        <table id="list_produk" class="table table-bordered table-hover" style="width: 100%">
                            <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Item</th>
                                <th>Stock</th>
                                <th>Last Price</th>
                                <th>Price</th>
                                <th>Quantity Left</th>
                                <th>Quantity</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="card-footer bg-gradient-secondary">
                        <div class="row justify-content-between">
                            <div class="col-sm-12 col-md-4 col-lg-2">
                                <a href="{{ route('admin.purchasing.invoicing.view.index') }}" class="btn btn-block btn-outline-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-2 mt-2 mt-lg-0 mt-sm-2">
                                <button type="submit" class="btn btn-block btn-success" id="btn-save"><i class="fas fa-check mr-2"></i>Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script src="{{ asset('js/select2.js') }}"></script>
    <script src="{{ asset('js/fancybox.js') }}"></script>
    <script src="{{ asset('js/numeral.js') }}"></script>
    <script src="{{ asset('js/cleave.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script type="text/javascript">
        function nilaiTotal(quantity,price,result = 0) {
            quantity = parseInt(quantity);
            price = parseInt(price);

            if (Number.isNaN(quantity)) quantity = 0;
            if (Number.isNaN(price)) price = 0;

            if (quantity !== 0 || price !== 0) {
                result = quantity*price;
            }

            return result;
        }

        let invoicing_info_id = () => document.getElementById('invoicing_info_id').value;
        let store_requisition_info_id = () => document.getElementById('store_requisition_info_id').value;

        let data_cleave = {
            store_requisition_product_id: [],
            product_id: [],
            input_quantity: [],
            input_price: []
        }

        let list_product_counter = 0;
        const list_produk = $('#list_produk').DataTable({
            scrollX: true,
            columns: [
                {data: 'product_code', className: "align-middle font-weight-bold", width: '5%'},
                {data: 'product_name', className: "align-middle"},
                {data: 'product_stock', width: '10%', className: "align-middle text-right"},
                {data: 'last_price', width: '10%', className: "align-middle text-right"},
                {data: 'price', orderable: false, width: '15%', className: "align-middle text-right"},
                {data: 'quantity_max', width: '10%', className: "align-middle text-right"},
                {data: 'quantity', orderable: false, width: '10%'},
            ],
            columnDefs: [
                {
                    targets: 2,
                    render: (data, type, row, meta) => {
                        return numeral(data).format('0,0') + ' ' + row.satuan;
                    }
                },
                {
                    targets: 3,
                    render: (data, type, row, meta) => {
                        return 'Rp '+numeral(data).format('0,0');
                    }
                },
                {
                    targets: 4,
                    render: (data, type, row, meta) => {
                        return `<input type="text" class="form-control text-right border-primary input-price" data-id="${list_product_counter}" name="price[]" value="${data}">`;
                    }
                },
                {
                    targets: 5,
                    render: (data, type, row, meta) => {
                        return numeral(data - row.quantity_sent).format('0,0') + ' ' + row.satuan;
                    }
                },
                {
                    targets: 6,
                    render: (data, type, row, meta) => {
                        return `<input type="text" class="form-control text-right border-primary input-quantity" data-id="${list_product_counter}" name="quantity[]">`;
                    }
                },
            ],
            drawCallback: function (setting) {
                let api = this.api();
                let lastRow = (api.data().length) - 1;

                if (list_product_counter > 0 && api.data().length > 0 && $(`.input-quantity[data-id="${list_product_counter}"]`).length) {
                    data_cleave.store_requisition_product_id[list_product_counter] = api.rows( {page:'current'} ).data()[lastRow].store_requisition_product_id;
                    data_cleave.product_id[list_product_counter] = api.rows( {page:'current'} ).data()[lastRow].product_id;
                    data_cleave.input_price[list_product_counter] = new Cleave(`.input-price[data-id="${list_product_counter}"]`, {
                        numeral: true,
                        numeralThousandsGroupStyle: 'thousand'
                    });
                    data_cleave.input_quantity[list_product_counter] = new Cleave(`.input-quantity[data-id="${list_product_counter}"]`, {
                        numeral: true,
                        numeralThousandsGroupStyle: 'thousand'
                    });
                    list_product_counter++;
                } else {
                    list_product_counter++;
                }
            }
        });

        document.addEventListener("DOMContentLoaded", () => {
            axios({
                url: '{{ route('admin.purchasing.invoicing.api.products') }}',
                method: 'post',
                data: {
                    store_requisition_info_id: store_requisition_info_id()
                }
            }).then(response => {
                response.data.forEach((v,i) => {
                    if (v.quantity_max > v.quantity_sent) {
                        list_produk.row.add(v).draw();
                    }
                })
            })

            const t_list_product_tbody = $('#t_list_product tbody');
            const t_list_product_data = row => t_list_product.row( row ).data();

            t_list_product_tbody.on('click','button.action-add-product', function (event) {
                let data = t_list_product_data($(event.target).parents('tr'));
                list_produk.row.add( data ).draw();
                listProductModal.modal('hide')
            });

            const list_produk_tbody = $('#list_produk tbody');
            const list_produk_data = row => list_produk.row( row ).data();

            list_produk_tbody.on( 'keyup', 'input.input-quantity', function (event) {
                let data = list_produk_data($(event.target).parents('tr'));

                let data_id = parseInt($(this).attr('data-id'));

                let quantity = data_cleave.input_quantity[data_id].getRawValue();

                let quantity_left = data.quantity_max - data.quantity_sent;
                quantity_left = (data.product_stock > quantity_left) ? quantity_left : data.product_stock;

                console.log(quantity,quantity_left);
                if (quantity > quantity_left) {
                    data_cleave.input_quantity[data_id].setRawValue(quantity_left)
                }
            });

            document.getElementById('form-data').addEventListener('submit', event => {
                event.preventDefault()

                let data_product = [];

                data_cleave.product_id.forEach((v,i) => {
                    data_product.push({
                        store_requisition_product_id: parseInt(data_cleave.store_requisition_product_id[i]),
                        product_id: v,
                        quantity: parseInt(data_cleave.input_quantity[i].getRawValue()),
                        price: parseInt(data_cleave.input_price[i].getRawValue())
                    })
                })

                axios({
                    url: '{{ route('admin.purchasing.invoicing.api.store') }}',
                    method: 'post',
                    data: {
                        product: data_product,
                        invoicing_info_id: invoicing_info_id(),
                        store_requisition_info_id: store_requisition_info_id(),
                    }
                }).then(response => {
                    if (response.data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Data Tersimpan',
                            showDenyButton: true,
                            reverseButtons: true,
                            confirmButtonText: `Cetak PDF`,
                            denyButtonText: `Selesai`,
                            confirmButtonColor: '#2eb85c',
                            denyButtonColor: '#321fdb',
                            willClose(popup) {
                                window.location = response.data.redirect
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.open(response.data.invoice_pdf)
                            } else if (result.isDenied) {
                                window.location = response.data.redirect
                            }
                        })
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Gagal Tersimpan'
                        });
                    }
                }).catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terdapat Kesalahan Pada System',
                        text: error.response.data.message,
                    });
                })
            });
        });
    </script>
@endsection
