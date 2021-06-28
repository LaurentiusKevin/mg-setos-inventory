@extends('admin._layout')

@section('title','Purchasing - Invoicing - Info')

@section('description','')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fancybox.css') }}">
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">Purchasing</li>
    <li class="breadcrumb-item">Invoicing</li>
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
                        <dt class="col-sm-2">Nomor Invoice</dt>
                        <dd class="col-sm-10 font-weight-bold">{{ $info->invoice_number_invoicing }}</dd>

                        <dt class="col-sm-2">Info Penggunaan</dt>
                        <dd class="col-sm-10">{{ $info->info_penggunaan }}</dd>

                        <dt class="col-sm-2">Catatan</dt>
                        <dd class="col-sm-10">{{ $info->catatan ?? '-' }}</dd>
                    </dl>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <div class="p-2 bd-highlight">
                            <a href="{{ route('admin.purchasing.invoicing.view.index') }}" class="btn btn-block btn-outline-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
                        </div>
                        <div class="p-2 bd-highlight">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('admin.purchasing.invoicing.view.invoice-details',[$info->id]) }}" target="_blank" class="btn btn-outline-primary"><i class="fas fa-file-pdf mr-2"></i> Detail</a>
                                <a href="{{ route('admin.purchasing.invoicing.view.invoice-summary',[$info->id]) }}" target="_blank" class="btn btn-outline-primary"><i class="fas fa-file-pdf mr-2"></i> Summary</a>
                            </div>
                        </div>
                    </div>
{{--                    <div class="row justify-content-between">--}}
{{--                        <div class="col-sm-12 col-md-4 col-lg-2">--}}
{{--                            <a href="{{ route('admin.purchasing.invoicing.view.index') }}" class="btn btn-block btn-outline-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>--}}
{{--                        </div>--}}
{{--                        <div class="col-sm-12 col-md-3 col-lg-2">--}}
{{--                            <div class="btn-group" role="group" aria-label="Basic example">--}}
{{--                                <a href="{{ route('admin.purchasing.invoicing.view.invoice',[$info->id]) }}" target="_blank" class="btn btn-outline-primary btn-block"><i class="fas fa-file-pdf mr-2"></i> Details</a>--}}
{{--                                <a href="{{ route('admin.purchasing.invoicing.view.invoice',[$info->id]) }}" target="_blank" class="btn btn-outline-primary btn-block"><i class="fas fa-file-pdf mr-2"></i> Summary</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <strong>Produk</strong>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover" id="product-table" style="width: 100%">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Produk</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Penginput</th>
                            <th>Tanggal</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($product AS $key => $item)
                            <tr>
                                <td class="text-nowrap" style="width: 2%">{{ $key+1 }}</td>
                                <td class="font-weight-bold text-nowrap" style="width: 5%">{{ $item->product_code }}</td>
                                <td>{{ $item->product_name }}</td>
                                <td class="text-right">{{ number_format($item->quantity_sent,0,',','.').' '.$item->satuan }}</td>
                                <td class="text-right">Rp {{ number_format($item->price,0,',','.') }}</td>
                                <td>{{ $item->penginput }}</td>
                                <td class="text-nowrap" style="width: 5%">{{ date('d F Y, H:i:s',strtotime($item->invoicing_created_at)) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $('#product-table').DataTable({
            scrollX: true
        });
    </script>
@endsection
