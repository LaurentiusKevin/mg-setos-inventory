@extends('admin._layout')

@section('title','Stock - Store Requisition - Create')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Stock</li>
    <li class="breadcrumb-item">Store Requisition</li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/datatables.css') }}">
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endsection

@section('content')
    <form id="form-data">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <strong>Info</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="i_department" class="font-weight-bold">Department</label>
                                    <select id="i_department" name="info" class="form-control" required>
                                        @if(count($department) > 1)
                                            <option value="">-- Pilih Department --</option>
                                        @endif
                                        @foreach($department AS $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="i_info_penggunaan" class="font-weight-bold">Digunakan Untuk</label>
                                    <textarea id="i_info_penggunaan" name="info" class="form-control" required></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="i_catatan" class="font-weight-bold">Catatan Tambahan</label>
                                    <textarea id="i_catatan" name="info" class="form-control"></textarea>
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
                        <div class="card-header-actions">
                            <div class="card-header-actions">
                                <button type="button" class="btn btn-success btn-block btn-sm" id="add_product">Tambah Item</button>
                            </div>
                        </div>
                        <strong>Items</strong>
                    </div>
                    <div class="card-body">
                        <form id="formData">
                            <div class="card-body">
                                <table id="list_penggunaan_produk" class="table table-bordered table-hover" style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Item</th>
                                        <th>Stock</th>
                                        <th>Last Price</th>
                                        <th>Avg Price</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer bg-gradient-secondary">
                        <div class="row justify-content-between">
                            <div class="col-sm-12 col-md-4 col-lg-2">
                                <a href="{{ route('admin.stock.store-requisition.view.index') }}" class="btn btn-block btn-outline-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
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
    <div class="modal fade" tabindex="-1" id="list_produk">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">List Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-hover" id="t_list_product" style="width: 100%">
                        <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Produk</th>
                            <th>Stok</th>
                            <th>Supplier Price</th>
                            <th>Last Price</th>
                            <th>Average Price</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

        let department = () => document.getElementById('i_department').value;
        let info_penggunaan = () => document.getElementById('i_info_penggunaan').value;
        let catatan = () => document.getElementById('i_catatan').value;

        let data_cleave = {
            product_id: [],
            input_quantity: [],
            price: []
        }
        let listProductModal = $('#list_produk');

        let list_penggunaan_counter = 0;
        const list_penggunaan_produk = $('#list_penggunaan_produk').DataTable({
            scrollX: true,
            ordering: false,
            columns: [
                {data: 'code', width: '5%', className: "align-middle font-weight-bold text-nowrap"},
                {data: 'name', className: "align-middle font-weight-bold"},
                {data: 'stock', width: '10%', className: "align-middle text-right"},
                {data: 'last_price', width: '10%', className: "align-middle text-right"},
                {data: 'avg_price', width: '10%', className: "align-middle text-right"},
                {data: 'supplier_price', width: '10%', className: "align-middle text-right"},
                {data: null, width: '10%'},
                {data: null, width: '5%', className: "align-middle text-center"},
            ],
            columnDefs: [
                {
                    targets: 2,
                    render: (data, type, row, meta) => {
                        return numeral(data).format('0,0')+' '+row.satuan;
                    }
                },
                {
                    targets: 3,
                    render: (data, type, row, meta) => {
                        return `<div class="d-flex justify-content-between"><div class="p-2 bd-highlight">Rp </div><div class="p-2 bd-highlight">${numeral(data).format('0,0')}</div></div>`;
                    }
                },
                {
                    targets: 4,
                    render: (data, type, row, meta) => {
                        return `<div class="d-flex justify-content-between"><div class="p-2 bd-highlight">Rp </div><div class="p-2 bd-highlight">${numeral(data).format('0,0')}</div></div>`;
                    }
                },
                {
                    targets: 5,
                    render: (data, type, row, meta) => {
                        return `<input type="text" class="form-control text-right border-primary input-price" data-id="${list_penggunaan_counter}" name="price[]" value="${data}">`;
                    }
                },
                {
                    targets: 6,
                    render: (data, type, row, meta) => {
                        return `<input type="text" class="form-control text-right border-primary input-quantity" data-id="${list_penggunaan_counter}" name="quantity[]">`;
                    }
                },
                {
                    targets: 7,
                    render: (data, type, row, meta) => {
                        return `<button type="button" class="btn btn-ghost-danger action-remove-product" data-id="${list_penggunaan_counter}"><i class="fas fa-times"></i></button>`;
                    }
                },
            ],
            drawCallback: function (setting) {
                let api = this.api();
                let lastRow = (api.data().length) - 1;

                if (list_penggunaan_counter > 0 && api.data().length > 0 && $(`.input-quantity[data-id="${list_penggunaan_counter}"]`).length) {
                    data_cleave.product_id[list_penggunaan_counter] = api.rows( {page:'current'} ).data()[lastRow].id;
                    data_cleave.input_quantity[list_penggunaan_counter] = new Cleave(`.input-quantity[data-id="${list_penggunaan_counter}"]`, {
                        numeral: true,
                        numeralThousandsGroupStyle: 'thousand'
                    });
                    data_cleave.price[list_penggunaan_counter] = new Cleave(`.input-price[data-id="${list_penggunaan_counter}"]`, {
                        numeral: true,
                        numeralThousandsGroupStyle: 'thousand'
                    });
                    list_penggunaan_counter++;
                } else {
                    list_penggunaan_counter++;
                }
            }
        });

        let selected_product_id = [];
        const t_list_product = $('#t_list_product').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                url: '{{ route('admin.stock.store-requisition.api.product-list') }}',
                method: 'post',
                data: function (d) {
                    d.selected_product_id = selected_product_id;
                }
            },
            columns: [
                {data: 'code', name: 'products.code', className: "align-middle text-dark font-weight-bold"},
                {data: 'name', name: 'products.name', className: "align-middle"},
                {data: 'stock', name: 'products.stock', className: "align-middle text-right"},
                {data: 'supplier_price', name: 'products.supplier_price', className: "align-middle text-right"},
                {data: 'last_price', name: 'products.last_price', className: "align-middle text-right"},
                {data: 'avg_price', name: 'products.avg_price', className: "align-middle text-right"},
                {data: 'action', searchable: false, orderable: false, width: '5%', className: "align-middle text-center"},
            ],
            columnDefs: [
                {
                    targets: 2,
                    render: (data, type, row, meta) => {
                        return `<span class="text-nowrap">${numeral(data).format('0,0')} ${row.satuan}</span>`;
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
                        return 'Rp '+numeral(data).format('0,0');
                    }
                },
                {
                    targets: 5,
                    render: (data, type, row, meta) => {
                        return 'Rp '+numeral(data).format('0,0');
                    }
                },
                {
                    targets: 6,
                    render: (data, type, row, meta) => {
                        return `<button class="btn btn-ghost-success action-add-product"><i class="fas fa-plus"></i></button>`;
                    }
                },
            ],
        });

        document.addEventListener("DOMContentLoaded", () => {
            document
                .getElementById('add_product')
                .addEventListener('click', event => {
                    let data = list_penggunaan_produk.data();
                    selected_product_id = [];
                    data.each(function (v) {
                        selected_product_id.push(v.id);
                    });
                    t_list_product.ajax.reload();
                    listProductModal.modal('show');
                });

            listProductModal.on('shown.bs.modal', function (event) {
                t_list_product.columns.adjust();
            })

            listProductModal.on('hidden.bs.modal', function (event) {
                list_penggunaan_produk.columns.adjust();
            })

            const t_list_product_tbody = $('#t_list_product tbody');
            const t_list_product_data = row => t_list_product.row( row ).data();

            t_list_product_tbody.on('click','button.action-add-product', function (event) {
                let data = t_list_product_data($(event.target).parents('tr'));
                list_penggunaan_produk.row.add( data ).draw();
                listProductModal.modal('hide')
            });

            const list_penggunaan_produk_tbody = $('#list_penggunaan_produk tbody');
            const list_penggunaan_produk_data = row => list_penggunaan_produk.row( row ).data();

            list_penggunaan_produk_tbody.on( 'click', 'button.action-remove-product', function () {
                let data_id = parseInt($(this).attr('data-id'));
                data_cleave.input_quantity[data_id].destroy();

                data_cleave.product_id.splice(data_id,1);
                data_cleave.input_quantity.splice(data_id,1);

                list_penggunaan_produk
                    .row( $(this).parents('tr') )
                    .remove()
                    .draw();
            });

            document.getElementById('form-data').addEventListener('submit', event => {
                event.preventDefault()

                let data_product = [];

                data_cleave.product_id.forEach((v,i) => {
                    data_product.push({
                        product_id: v,
                        quantity: parseInt(data_cleave.input_quantity[i].getRawValue()),
                        price: parseInt(data_cleave.price[i].getRawValue())
                    })
                });

                axios({
                    url: '{{ route('admin.stock.store-requisition.api.store') }}',
                    method: 'post',
                    data: {
                        department: department(),
                        product: data_product,
                        info_penggunaan: info_penggunaan(),
                        catatan: catatan(),
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
                            title: response.data.message
                        });
                    }
                }).catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terdapat Kesalahan Pada System',
                        text: error.response.data.message,
                    });
                });
            });
        });
    </script>
@endsection
