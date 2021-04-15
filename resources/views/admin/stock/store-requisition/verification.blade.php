@extends('admin._layout')

@section('title','Stock - Store Requisition - Info')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Stock</li>
    <li class="breadcrumb-item">Store Requisition</li>
    <li class="breadcrumb-item active">Info</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-header-actions">
                        <div class="card-header-actions">
                            <button class="btn btn-success btn-block btn-sm" id="action-verification">
                                <i class="fas fa-check mr-2"></i> VERIFIKASI
                            </button>
                        </div>
                    </div>
                    <strong>Info</strong>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-2">Penginput</dt>
                        <dd class="col-sm-10">{{ $info->penginput }}</dd>

                        <dt class="col-sm-2">Department</dt>
                        <dd class="col-sm-10">{{ $info->department }}</dd>

                        <dt class="col-sm-2">Digunakan Untuk</dt>
                        <dd class="col-sm-10">{{ $info->info_penggunaan }}</dd>

                        <dt class="col-sm-2">Catatan</dt>
                        <dd class="col-sm-10">{{ $info->catatan ?? '-' }}</dd>
                    </dl>
                </div>
                <div class="card-footer">
                    <div class="row justify-content-between">
                        <div class="col-sm-12 col-md-4 col-lg-2">
                            <a href="{{ route('admin.stock.store-requisition.view.index') }}" class="btn btn-block btn-outline-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-2">
                            <a href="{{ route('admin.stock.store-requisition.view.invoice',[$info->id]) }}" target="_blank" class="btn btn-outline-primary btn-block"><i class="fas fa-file-pdf mr-2"></i> PDF</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <strong>Produk</strong>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Avg Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($product AS $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td class="text-right">{{ number_format($item->quantity,0,',','.').' '.$item->satuan }}</td>
                                <td class="text-right">Rp {{ number_format($item->price,0,',','.') }}</td>
                                <td class="text-right">Rp {{ number_format($item->avg_price,0,',','.') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <strong>Catatan</strong>
                </div>
                <div class="card-body">
                    <input type="hidden" id="store_requisition_info_id" value="{{ $info->id }}">
                    @if(count($catatan) > 0)
                        <ol>
                            @foreach($catatan AS $key => $item)
                                <li>{{ $item->catatan }}</li>
                            @endforeach
                        </ol>
                    @endif
                    <div class="form-group">
                        <label for="i_catatan" class="font-weight-bold">Catatan</label>
                        <textarea id="i_catatan" name="info" class="form-control"></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row justify-content-end">
                        <div class="col-sm-12 col-md-2 col-lg-2">
                            <button type="button" class="btn btn-outline-success btn-block" id="store-note">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        let catatan = () => document.getElementById('i_catatan').value;
        let store_requisition_info_id = () => document.getElementById('store_requisition_info_id').value;

        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById('action-verification').addEventListener('click', event => {
                Swal.fire({
                    title: 'Verifikasi SR ini?',
                    icon: 'warning',
                    reverseButtons: true,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Verifikasi!'
                }).then((result) => {
                    Swal.showLoading();
                    if (result.isConfirmed) {
                        axios({
                            url: '{{ route('admin.stock.store-requisition.api.store-verification') }}',
                            method: 'post',
                            data: {
                                store_requisition_info_id: store_requisition_info_id(),
                            }
                        }).then(response => {
                            if (response.data.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Data Tersimpan',
                                    showConfirmButton: false,
                                    timer: 1200,
                                    willClose(popup) {
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
                    }
                })
            })

            document.getElementById('store-note').addEventListener('click', event => {
                event.preventDefault()

                axios({
                    url: '{{ route('admin.stock.store-requisition.api.store-catatan') }}',
                    method: 'post',
                    data: {
                        store_requisition_info_id: store_requisition_info_id(),
                        catatan: catatan(),
                    }
                }).then(response => {
                    if (response.data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Data Tersimpan',
                            showConfirmButton: false,
                            timer: 1200,
                            willClose(popup) {
                                window.location.reload();
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
