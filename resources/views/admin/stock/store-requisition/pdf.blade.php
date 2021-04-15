<!DOCTYPE html>
<html lang="id">
<head>
    <title>Store Requisition {{ $info->invoice_number }}</title>
    <link rel="stylesheet" href="{{ public_path('css/pdf/portrait.css') }}">
</head>
<body>
<header>
    <table style="width: 100%">
        <tr>
            <td class="header-brt" style="width: 30%">STORE REQUISITION</td>
            <td style="width: 20%"></td>
            <td rowspan="2" style="width: 50%">
                <img src="{{ public_path('icons/picture.svg') }}" class="logo" alt="Logo">
            </td>
        </tr>
        <tr>
            <td class="invoice-number" style="height: 1cm">
                <span class="text-bold" style="font-size: 10px; color: indianred;">Nomor</span>
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
            <td class="text-bold" style="width: 40%; color: indianred; font-size: 10px">Digunakan Untuk</td>
            <td class="text-bold" style="width: 30%; color: indianred; font-size: 10px">Dibuat Oleh</td>
            <td class="text-bold" style="width: 30%; color: indianred; font-size: 10px">Catatan</td>
        </tr>
        <tr>
            <td style="font-size: 14px">{{ $info->info_penggunaan }}</td>
            <td style="font-size: 14px; vertical-align: top">{{ $info->penginput ?? '-' }}</td>
            <td style="font-size: 14px; vertical-align: top">{{ $info->catatan ?? '-' }}</td>
        </tr>
    </table>
    <hr>
    <table class="table-transaksi">
        <thead style="background-color: indianred; color: white">
        <tr class="text-center">
            <th class="text-center" style="width: 8%">No</th>
            <th style="width: 40%">Produk</th>
            <th style="width: 20%">Quantity</th>
            <th style="width: 20%">Harga</th>
            <th style="width: 20%">Total</th>
        </tr>
        </thead>
        <tbody>
        @php($total = 0)
        @foreach($product AS $key => $item)
            <tr>
                <td class="text-center">{{ $key+1 }}</td>
                <td>{{ $item->product_name }}</td>
                <td class="text-right">{{ number_format($item->quantity,0,',','.').' '.$item->satuan }}</td>
                <td class="text-right">{{ number_format($item->price,0,',','.') }}</td>
                <td class="text-right">{{ number_format($item->quantity * $item->price,0,',','.') }}</td>
            </tr>
            @php($total += $item->quantity * $item->price)
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="4" class="text-right text-bold">Total</td>
            <td class="text-right text-bold">{{ number_format($total,0,',','.') }}</td>
        </tr>
        </tfoot>
    </table>
</main>
</body>
</html>
