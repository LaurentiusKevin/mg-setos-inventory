<!DOCTYPE html>
<html lang="id">
<head>
    <title>Purchase Order {{ $data->invoice_number }}</title>
    <link rel="stylesheet" href="{{ public_path('css/pdf/portrait.css') }}">
</head>
<body>
<header>
    <table style="width: 100%">
        <tr>
            <td class="header-brt" style="width: 30%">PURCHASE ORDER</td>
            <td style="width: 20%"></td>
            <td rowspan="2" style="width: 50%">
                <img src="{{ public_path('icons/picture.svg') }}" class="logo" alt="Logo">
            </td>
        </tr>
        <tr>
            <td class="invoice-number" style="height: 1cm">
                NOMOR
                <br>{{ $data->invoice_number }}
            </td>
        </tr>
    </table>
    <hr>
</header>

<footer>
    <table style="width: 100%">
        <tr>
            <td>Dicetak oleh {{ auth()->user()->name }} ({{ date('d F Y - H:i:s') }})</td>
        </tr>
    </table>
</footer>

<main>
    <table style="width: 100%">
        <tr>
            <td class="text-bold" style="width: 40%; color: indianred; font-size: 14px">Supplier</td>
            <td class="text-bold" style="width: 20%; color: indianred; font-size: 14px"></td>
            <td class="text-bold" style="width: 40%; color: indianred; font-size: 14px">Catatan</td>
        </tr>
        <tr>
            <td>
                <span style="font-size: 13px">{{ $data->supplier->name }}</span>
                <br>{{ $data->supplier->address }}
                <br>{{ $data->supplier->phone }}
            </td>
            <td></td>
            <td style="vertical-align: top">{{ $data->catatan }}</td>
        </tr>
    </table>
    <hr>
    <table class="table-transaksi">
        <thead style="background-color: indianred; color: white">
        <tr class="text-center">
            <th class="text-center" style="width: 8%">No</th>
            <th style="width: 40%">Produk</th>
            <th style="width: 20%">Qty</th>
            <th style="width: 20%">Price</th>
            <th style="width: 20%">Amount</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data->products AS $key => $item)
            <tr>
                <td class="text-center">{{ $key+1 }}</td>
                <td>{{ $item->product->name }}</td>
                <td class="text-right">{{ number_format($item->quantity,0,',','.').' '.$item->product->satuan->nama }}</td>
                <td class="text-right">{{ number_format($item->price,0,',','.') }}</td>
                <td class="text-right">{{ number_format($item->total_price,0,',','.') }}</td>
            </tr>
        @endforeach
        <tr style="background-color: indianred; color: white">
            <th class="text-center" colspan="4">Total</th>
            <th class="text-right">{{ number_format($data->total_price,0,',','.') }}</th>
        </tr>
        </tbody>
    </table>
</main>
</body>
</html>