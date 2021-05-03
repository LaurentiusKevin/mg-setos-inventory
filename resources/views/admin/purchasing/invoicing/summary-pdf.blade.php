<!DOCTYPE html>
<html lang="id">
<head>
    <title>Invoicing {{ $info->invoice_number_invoicing }}</title>
    <link rel="stylesheet" href="{{ public_path('css/pdf/portrait.css') }}">
</head>
<body>
<header>
    <table style="width: 100%">
        <tr>
            <td class="header-brt" style="width: 30%">INVOICING</td>
            <td style="width: 20%"></td>
            <td rowspan="2" style="width: 50%">
                <img src="{{ public_path('icons/picture.svg') }}" class="logo" alt="Logo">
            </td>
        </tr>
        <tr>
            <td class="invoice-number" style="height: 1cm">
                <span class="text-bold" style="font-size: 8px; color: indianred;">NOMOR</span>
                <br>{{ $info->invoice_number_invoicing }}
            </td>
        </tr>
    </table>
    <hr>
</header>

<footer>
    <table style="width: 100%">
        <tr>
            <td style="width: 40%" colspan="2"></td>
            <td style="width: 40%">
                Tertanda Tangan Secara Digital
                <br>Pada: {{ date('d-m-Y, H:i:s',strtotime($info->updated_at)) }}
                <br>
                <br><strong style="font-size: 13px">{{ $info->penginput_invoicing }}</strong>
            </td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 1cm">
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
            <th class="text-center" style="width: 6%">No</th>
            <th style="width: 10%">Kode</th>
            <th>Produk</th>
            <th style="width: 15%">Quantity</th>
            <th style="width: 15%">Price</th>
            <th style="width: 20%">Subtotal</th>
        </tr>
        </thead>
        <tbody>
        @php($total = 0)
        @foreach($product AS $key => $item)
            <tr>
                <td class="text-center" style="width: 6%">{{ $key+1 }}</td>
                <td class="font-weight-bold text-nowrap" style="width: 10%">{{ $item->product_code }}</td>
                <td>{{ $item->product_name }}</td>
                <td class="text-right">{{ number_format($item->quantity_sent,0,',','.').' '.$item->satuan }}</td>
                <td class="text-right">Rp {{ number_format($item->price,0,',','.') }}</td>
                <td class="text-right">Rp {{ number_format($item->quantity_sent * $item->price,0,',','.') }}</td>
            </tr>
            @php($total += $item->quantity_sent * $item->price)
        @endforeach
        <tr style="background-color: rgba(205,92,92,0.47)">
            <td class="text-center text-bold" colspan="5">TOTAL</td>
            <td class="text-right text-bold">Rp {{ number_format($total,0,',','.') }}</td>
        </tr>
        </tbody>
    </table>
</main>
</body>
</html>
