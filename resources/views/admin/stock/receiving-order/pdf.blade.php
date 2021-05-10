<!DOCTYPE html>
<html lang="id">
<head>
    <title>Receiving Order {{ $info->invoice_number }}</title>
    <link rel="stylesheet" href="{{ public_path('css/pdf/portrait.css') }}">
</head>
<body>
<header>
    <table style="width: 100%">
        <tr>
            <td class="header-brt" style="width: 30%">RECEIVING ORDER</td>
            <td style="width: 20%"></td>
            <td rowspan="2" style="width: 50%">
                <img src="{{ public_path('icons/logo-mg-setos-hotel.png') }}" class="logo" alt="Logo" style="width: 60%">
            </td>
        </tr>
        <tr>
            <td class="invoice-number" style="height: 1cm">
                <span class="text-bold text-setos" style="font-size: 10px">NOMOR</span>
                <br>{{ $info->invoice_number }}
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
            <td class="text-bold text-setos" style="width: 40%; font-size: 10px">Supplier</td>
            <td class="text-bold text-setos" style="width: 20%; font-size: 10px"></td>
            <td class="text-bold text-setos" style="width: 40%; font-size: 10px">Catatan</td>
        </tr>
        <tr>
            <td>
                <span class="text-bold">{{ $info->supplier_name }}</span>
                <br>{{ $info->supplier_address }}
                <br>{{ $info->supplier_phone }}
            </td>
            <td></td>
            <td style="vertical-align: top">{{ $info->catatan }}</td>
        </tr>
    </table>
    <hr>
    <table class="table-transaksi">
        <thead class="bg-setos" style="color: white">
        <tr class="text-center">
            <th style="width: 8%">No</th>
            <th style="width: 40%">Produk</th>
            <th style="width: 20%">Qty</th>
            <th style="width: 20%">Price (Rp)</th>
            <th style="width: 20%">Amount (Rp)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($product AS $key => $item)
            <tr>
                <td class="text-center">{{ $key+1 }}</td>
                <td>{{ $item->name }}</td>
                <td class="text-right">{{ number_format($item->quantity,0,',','.').' '.$item->satuan }}</td>
                <td class="text-right">{{ number_format($item->price,0,',','.') }}</td>
                <td class="text-right">{{ number_format($item->total_price,0,',','.') }}</td>
            </tr>
        @endforeach
        <tr>
            <th class="text-right" colspan="4">Total</th>
            <th class="text-right">{{ number_format($info->total_price,0,',','.') }}</th>
        </tr>
        </tbody>
    </table>
</main>
</body>
</html>
