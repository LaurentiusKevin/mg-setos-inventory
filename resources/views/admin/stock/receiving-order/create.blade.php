@extends('admin._layout')

@section('title','Purchasing - Receiving Order - Create')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Purchasing</li>
    <li class="breadcrumb-item">Receiving Order</li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/datatables.css') }}">
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-header-actions">
                        <div class="card-header-actions">
                            <button class="btn btn-success btn-block btn-sm" id="btn-pilih-po">Pilh PO</button>
                        </div>
                    </div>
                    <strong>Data Purhase Order</strong>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="info_no_po">No. Purchase Order</label>
                        <br><span class="font-weight-bold" id="info_no_po">-</span>
                    </div>
                    <div class="d-flex flex-row bd-highlight">
                        <div class="p-2 bd-highlight"><img id="info_logo" src="{{ asset('icons/picture.svg') }}" alt="image" style="width: 75px"></div>
                        <div class="p-2 flex-grow-1 bd-highlight">
                            <div class="row">
                                <div class="col-sm-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="info_supplier">Nama Supplier</label>
                                        <br><span class="font-weight-bold" id="info_supplier">-</span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="info_telp">Telp.</label>
                                        <br><span class="font-weight-bold" id="info_telp">-</span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="info_alamat">Alamat</label>
                                        <br><span class="font-weight-bold" id="info_alamat">-</span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="info_cp_nama">Nama CP</label>
                                        <br><span class="font-weight-bold" id="info_cp_nama">-</span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="info_cp_telp">Telp CP</label>
                                        <br><span class="font-weight-bold" id="info_cp_telp">-</span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="info_catatan">Catatan</label>
                                        <br><span class="font-weight-bold" id="info_catatan">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>Produk</strong>
                </div>
                <form id="formData">
                    <input type="hidden" id="purchase_order_info_id" value="0">
                    <div class="card-body">
                        <table id="list_po_produk" class="table table-bordered table-hover" style="width: 100%">
                            <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Satuan</th>
                                <th>Last Price</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="i_info">Catatan Receiving</label>
                        <textarea id="i_info" name="info" class="form-control"></textarea>
                    </div>
                </div>
                <div class="card-footer bg-gradient-secondary">
                    <div class="row justify-content-between">
                        <div class="col-sm-12 col-md-4 col-lg-2">
                            <a href="{{ route('admin.stock.receiving-order.view.index') }}" class="btn btn-block btn-outline-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-2 mt-2 mt-lg-0 mt-sm-2">
                            <button type="button" class="btn btn-block btn-success" id="btn-save"><i class="fas fa-check mr-2"></i>Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="list_po">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">List Purchase Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-hover" id="t_list_po_pending" style="width: 100%">
                        <thead>
                        <tr>
                            <th>No. PO</th>
                            <th>Supplier</th>
                            <th>Total Harga</th>
                            <th>Catatan</th>
                            <th>Pilih</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Tambahkan</button>
                </div>
            </div>
        </div>
    </div>
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
        let purchase_order_info_id_val = () => data_cleave.purchase_order_info_id;
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

        function getProduct(purchase_order_info_id) {
            list_po_produk.clear().draw();

            axios({
                url: '{{ route('admin.stock.receiving-order.api.po-pending.products') }}',
                method: 'post',
                data: {
                    purchase_order_info_id: purchase_order_info_id
                }
            }).then(response => {
                response.data.forEach((v,i) => {
                    list_po_produk.row.add(v).draw();
                })
            }).catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Terdapat Kesalahan Pada System!'
                });
            })
        }

        let data_cleave = {
            purchase_order_info_id: null,
            purchase_order_product_id: [],
            product_id: [],
            input_quantity: [],
            input_price: []
        }

        let listPoModal = $('#list_po');

        let list_po_counter = -1;
        const list_po_produk = $('#list_po_produk').DataTable({
            scrollX: true,
            ordering: false,
            columns: [
                {data: 'product', className: "align-middle font-weight-bold"},
                {data: 'product'},
                {data: 'price', width: '15%'},
                {data: 'quantity', width: '15%'},
                {data: 'price', width: '15%'},
                {data: null, width: '15%'}
            ],
            columnDefs: [
                {
                    targets: 0,
                    render: (data) => {
                        return data.name;
                    }
                },
                {
                    targets: 1,
                    render: (data) => {
                        return data.satuan.nama;
                    }
                },
                {
                    targets: 2,
                    render: (data, type, row, meta) => {
                        return `<input type="text" class="form-control text-right text-white border-primary font-weight-bold bg-gradient-primary" value="${numeral(data).format('0,0')}" readonly>`;
                    }
                },
                {
                    targets: 3,
                    render: (data, type, row, meta) => {
                        return `<input type="text" class="form-control text-right border-primary input-quantity" data-id="${list_po_counter}" name="quantity[]">`;
                    }
                },
                {
                    targets: 4,
                    render: (data, type, row, meta) => {
                        return `<input type="text" class="form-control text-right border-primary input-price" data-id="${list_po_counter}" name="price[]" value="${data}">`;
                    }
                },
                {
                    targets: 5,
                    render: (data, type, row, meta) => {
                        return `<input type="text" class="form-control text-right font-weight-bold text-white bg-gradient-primary total-price" data-id="${list_po_counter}" name="total-price[]" readonly>`;
                    }
                }
            ],
            drawCallback: function (setting) {
                let api = this.api();
                let lastRow = (api.data().length) - 1;

                if (list_po_counter >= 0 && api.data().length > 0 && $(`.input-quantity[data-id="${list_po_counter}"]`).length) {
                    data_cleave.product_id[list_po_counter] = api.rows( {page:'current'} ).data()[lastRow].product_id;
                    data_cleave.purchase_order_product_id[list_po_counter] = api.rows( {page:'current'} ).data()[lastRow].id
                    data_cleave.input_quantity[list_po_counter] = new Cleave(`.input-quantity[data-id="${list_po_counter}"]`, {
                        numeral: true,
                        numeralThousandsGroupStyle: 'thousand'
                    });
                    data_cleave.input_price[list_po_counter] = new Cleave(`.input-price[data-id="${list_po_counter}"]`, {
                        numeral: true,
                        numeralThousandsGroupStyle: 'thousand'
                    });
                    list_po_counter++;
                } else {
                    list_po_counter++;
                }
            }
        });
        const list_po_produk_tbody = $('#list_po_produk tbody');
        const list_po_produk_data = row => list_po_produk.row( row ).data();

        let selected_product_id = [];
        const t_list_po_pending = $('#t_list_po_pending').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                url: '{{ route('admin.stock.receiving-order.api.po-pending') }}',
                method: 'post'
            },
            columns: [
                {data: 'invoice_number', className: "align-middle text-dark font-weight-bold"},
                {data: 'supplier', className: "align-middle"},
                {data: 'total_price', className: "align-middle text-right"},
                {data: 'catatan', className: "align-middle"},
                {data: 'action', width: '5%', 'orderable': false, className: "align-middle"},
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
                        return numeral(data).format('0,0');
                    }
                },
                {
                    targets: 4,
                    render: (data, type, row, meta) => {
                        return `<button class="btn btn-ghost-success action-add-po"><i class="fas fa-check"></i></button>`;
                    }
                },
            ],
        });
        const t_list_po_pending_tbody = $('#t_list_po_pending tbody');
        const t_list_po_pending_data = row => t_list_po_pending.row( row ).data();

        const supplier = () => i_supplier.select2('data')[0];
        const info_supplier = value => document.getElementById('info_supplier').innerHTML = value ?? '-';
        const info_logo = value => {
            if (value === null) {
                document.getElementById('info_logo').src = '{{ asset('icons/picture.svg') }}';
            } else {
                document.getElementById('info_logo').src = `{{ url('admin/master-data/supplier/api/get-image/') }}/${value}`;
            }
        }
        const info_no_po = value => document.getElementById('info_no_po').innerHTML = value ?? '-';
        const info_telp = value => document.getElementById('info_telp').innerHTML = value ?? '-';
        const info_alamat = value => document.getElementById('info_alamat').innerHTML = value ?? '-';
        const info_cp_nama = value => document.getElementById('info_cp_nama').innerHTML = value ?? '-';
        const info_cp_telp = value => document.getElementById('info_cp_telp').innerHTML = value ?? '-';
        const info_catatan = value => document.getElementById('info_catatan').innerHTML = value ?? '-';

        document.addEventListener("DOMContentLoaded", () => {
            document
                .getElementById('btn-pilih-po')
                .addEventListener('click', event => {
                    let data = list_po_produk.data();
                    selected_product_id = [];
                    data.each(function (v) {
                        selected_product_id.push(v.id);
                    });
                    t_list_po_pending.ajax.reload();
                    listPoModal.modal('show');
                });

            listPoModal.on('shown.bs.modal', function (event) {
                t_list_po_pending.columns.adjust();
            })

            listPoModal.on('hidden.bs.modal', function (event) {
                t_list_po_pending.columns.adjust();
            })

            t_list_po_pending_tbody.on('click','button.action-add-po', function (event) {
                let data = t_list_po_pending_data($(event.target).parents('tr'));
                data_cleave.purchase_order_info_id = data.id;

                info_supplier(data.supplier.name)
                info_logo(data.supplier.logo)
                info_no_po(data.invoice_number)
                info_telp(data.supplier.phone)
                info_alamat(data.supplier.address)
                info_cp_nama(data.supplier.contact_person_name)
                info_cp_telp(data.supplier.contact_person_phone)
                info_catatan(data.supplier.info)

                getProduct(data.id);

                listPoModal.modal('hide')
            });

            list_po_produk_tbody.on( 'click', 'button.action-remove-product', function () {
                let data_id = parseInt($(this).attr('data-id'));
                data_cleave.input_quantity[data_id].destroy();
                data_cleave.input_price[data_id].destroy();

                data_cleave.product_id.splice(data_id,1);
                data_cleave.input_quantity.splice(data_id,1);
                data_cleave.input_price.splice(data_id,1);

                list_po_produk
                    .row( $(this).parents('tr') )
                    .remove()
                    .draw();
            });

            list_po_produk_tbody.on( 'keyup', 'input.input-quantity', function (event) {
                let data = list_po_produk_data($(event.target).parents('tr'));

                let data_id = parseInt($(this).attr('data-id'));

                let quantity = data_cleave.input_quantity[data_id].getRawValue();

                let quantity_left = data.quantity - data.quantity_received;

                if (quantity > quantity_left) {
                    data_cleave.input_quantity[data_id].setRawValue(quantity_left)
                    quantity = quantity_left;
                }

                let price = data_cleave.input_price[data_id].getRawValue();

                $(this).parents('tr').find(`.total-price[data-id="${data_id}"]`).val(numeral(nilaiTotal(quantity,price)).format('0,0'));
            });

            list_po_produk_tbody.on( 'keyup', 'input.input-price', function () {
                let data_id = parseInt($(this).attr('data-id'));

                let quantity = data_cleave.input_quantity[data_id].getRawValue();
                let price = data_cleave.input_price[data_id].getRawValue();

                $(this).parents('tr').find(`.total-price[data-id="${data_id}"]`).val(numeral(nilaiTotal(quantity,price)).format('0,0'));
            });

            document.getElementById('btn-save').addEventListener('click', event => {
                event.preventDefault()

                let data_product = [];

                data_cleave.product_id.forEach((v,i) => {
                    let quantity = parseInt(data_cleave.input_quantity[i].getRawValue());
                    let price = parseInt(data_cleave.input_price[i].getRawValue());

                    if (!isNaN( quantity )) {
                        data_product.push({
                            purchase_order_product_id: data_cleave.purchase_order_product_id[i],
                            product_id: v,
                            quantity: quantity,
                            price: price
                        })
                    }
                })

                axios({
                    url: '{{ route('admin.stock.receiving-order.api.store') }}',
                    method: 'post',
                    data: {
                        purchase_order_info_id: data_cleave.purchase_order_info_id,
                        product: data_product,
                        catatan: info_catatan(),
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
