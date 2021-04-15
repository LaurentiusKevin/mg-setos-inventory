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
    </div>
@endsection
