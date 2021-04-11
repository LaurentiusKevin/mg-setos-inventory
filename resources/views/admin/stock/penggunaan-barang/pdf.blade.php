<!DOCTYPE html>
<html lang="id">
<head>
    <title>Penggunaan Barang {{ $info->invoice_number }}</title>
    <link rel="stylesheet" href="{{ public_path('css/pdf/portrait.css') }}">
</head>
<body>
<header>
    <table style="width: 100%">
        <tr>
            <td class="header-brt" style="width: 30%">PENGGUNAAN BARANG</td>
            <td style="width: 20%"></td>
            <td rowspan="2" style="width: 50%">
                <img src="{{ public_path('icons/picture.svg') }}" class="logo" alt="Logo">
            </td>
        </tr>
        <tr>
            <td class="invoice-number" style="height: 1cm">
                <span class="text-bold" style="font-size: 8px; color: indianred;">NOMOR</span>
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
            <td class="text-bold" style="width: 40%; color: indianred; font-size: 14px">Info Penggunaan</td>
            <td class="text-bold" style="width: 20%; color: indianred; font-size: 14px"></td>
            <td class="text-bold" style="width: 40%; color: indianred; font-size: 14px">Catatan</td>
        </tr>
        <tr>
            <td>
                <span style="font-size: 13px">{{ $info->info_penggunaan }}</span>
            </td>
            <td></td>
            <td style="vertical-align: top">{{ $info->catatan ?? '-' }}</td>
        </tr>
    </table>
    <hr>
    <table class="table-transaksi">
        <thead style="background-color: indianred; color: white">
        <tr class="text-center">
            <th class="text-center" style="width: 8%">No</th>
            <th style="width: 40%">Produk</th>
            <th style="width: 20%">Quantity</th>
        </tr>
        </thead>
        <tbody>
        @foreach($product AS $key => $item)
            <tr>
                <td class="text-center">{{ $key+1 }}</td>
                <td>{{ $item->product_name }}</td>
                <td class="text-right">{{ number_format($item->quantity,0,',','.').' '.$item->satuan }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</main>
</body>
</html>
