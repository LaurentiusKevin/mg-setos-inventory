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
            <td class="header-brt" style="width: 30%" colspan="2">INVOICING</td>
            <td style="width: 20%"></td>
            <td rowspan="2" style="width: 50%">
                <img src="{{ public_path('icons/logo-mg-setos-hotel.png') }}" class="logo" alt="Logo" style="width: 60%">
            </td>
        </tr>
        <tr>
            <td class="invoice-number" style="height: 1cm">
                <span class="text-bold text-setos" style="font-size: 8px;">NOMOR SR</span>
                <br>{{ $info->invoice_number_sr }}
            </td>
            <td class="invoice-number" style="height: 1cm; padding-left: .5cm">
                <span class="text-bold text-setos" style="font-size: 8px;">NOMOR INVOICE</span>
                <br>{{ $info->invoice_number_invoicing }}
            </td>
        </tr>
    </table>
    <hr>
</header>

<footer-tdd>
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
</footer-tdd>

<main>
    <table style="width: 100%">
        <tr>
            <td class="text-bold text-setos" style="width: 20%; font-size: 8px;">TANGGAL INVOICE</td>
            <td class="text-bold text-setos" style="width: 20%; font-size: 8px;">DEPARTEMEN</td>
            <td class="text-bold text-setos" style="width: 40%; font-size: 8px;">INFO PENGGUNAAN</td>
            <td class="text-bold text-setos" style="width: 40%; font-size: 8px;">CATATAN</td>
        </tr>
        <tr>
            <td style="font-size: 13px">{{ date('d F Y (H:i:s)',strtotime($info->completed_at)) }}</td>
            <td style="font-size: 13px; vertical-align: top">{{ $info->department_name }}</td>
            <td style="font-size: 13px; vertical-align: top">{{ $info->info_penggunaan }}</td>
            <td style="vertical-align: top">{{ $info->catatan ?? '-' }}</td>
        </tr>
    </table>
    <hr>
    <table class="table-transaksi">
        <thead class="bg-setos" style="color: white">
        <tr class="text-center">
            <th class="text-center" style="width: 6%">No</th>
            <th style="width: 10%">Kode</th>
            <th>Produk</th>
            <th style="width: 15%">Quantity</th>
            <th style="width: 15%">Price (Rp)</th>
            <th style="width: 20%">Subtotal (Rp)</th>
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
                <td class="text-right">{{ number_format(($item->total_price / $item->quantity_sent),0,',','.') }}</td>
                <td class="text-right">{{ number_format($item->total_price,0,',','.') }}</td>
            </tr>
            @php($total += $item->total_price)
        @endforeach
        <tr>
            <td class="text-right text-bold" colspan="5">TOTAL</td>
            <td class="text-right text-bold">{{ number_format($total,0,',','.') }}</td>
        </tr>
        </tbody>
    </table>
</main>
</body>
</html>
