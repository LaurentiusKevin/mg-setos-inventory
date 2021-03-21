@extends('admin._layout')

@section('title','Stock - Supplier - Info')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item">Stock</li>
    <li class="breadcrumb-item">Supplier</li>
    <li class="breadcrumb-item active">Info</li>
@endsection

@section('style')
    <link href="{{ asset('css/filepond.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fancybox.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <strong>Info</strong>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-row bd-highlight">
                        <div class="p-2 bd-highlight"><img id="info_logo" src="{{ $data->supplier->logo == null ? asset('icons/picture.svg') : url("admin/stock/supplier/api/get-image/{$data->supplier->logo}") }}" alt="image" style="width: 75px"></div>
                        <div class="p-2 flex-grow-1 bd-highlight">
                            <div class="row">
                                <div class="col-sm-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="info_supplier">Nama Supplier</label>
                                        <br><span class="font-weight-bold" id="info_supplier">{{ $data->supplier->name }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="info_telp">Telp.</label>
                                        <br><span class="font-weight-bold" id="info_telp">{{ $data->supplier->phone }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="info_alamat">Alamat</label>
                                        <br><span class="font-weight-bold" id="info_alamat">{{ $data->supplier->address }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="info_cp_nama">Nama CP</label>
                                        <br><span class="font-weight-bold" id="info_cp_nama">{{ $data->supplier->contact_person_name }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="info_cp_telp">Telp CP</label>
                                        <br><span class="font-weight-bold" id="info_cp_telp">{{ $data->supplier->contact_person_phone }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label for="info_catatan">Catatan</label>
                                        <br><span class="font-weight-bold" id="info_catatan">{{ $data->supplier->info ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row justify-content-between">
                        <div class="col-sm-12 col-md-4 col-lg-2">
                            <a href="{{ route('admin.stock.purchase-order.view.index') }}" class="btn btn-block btn-outline-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-2">
                            <a href="{{ route('admin.stock.purchase-order.view.invoice',[$data->id]) }}" target="_blank" class="btn btn-outline-primary btn-block"><i class="fas fa-file-pdf mr-2"></i> PDF</a>
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
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->products AS $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td class="text-right">{{ number_format($item->quantity,0,',','.').' '.$item->product->satuan->nama }}</td>
                                <td class="text-right">{{ number_format($item->price,0,',','.') }}</td>
                                <td class="text-right">{{ number_format($item->total_price,0,',','.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="bg-info">
                            <td class="font-weight-bold" colspan="3">Total</td>
                            <td class="text-right font-weight-bold">{{ number_format($data->total_price,0,',','.') }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/numeral.js') }}"></script>
@endsection
