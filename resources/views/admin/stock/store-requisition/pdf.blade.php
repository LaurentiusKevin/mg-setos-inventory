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
                <img src="{{ public_path('icons/logo-mg-setos-hotel.png') }}" class="logo" alt="Logo" style="width: 60%">
            </td>
        </tr>
        <tr>
            <td class="invoice-number" style="height: 1cm">
                <span class="text-bold text-setos" style="font-size: 10px;">Nomor</span>
                <br>{{ $info->invoice_number }}
            </td>
        </tr>
    </table>
    <hr>
</header>

<footer-tdd>
    <table style="width: 100%">
        <tr>
            <td style="width: 40%">
                Tertanda Tangan Secara Digital
                <br>Pada: {{ date('d-m-Y, H:i:s',strtotime($info->updated_at)) }}
                <br>
                <br><strong style="font-size: 13px">{{ $info->penginput }}</strong>
            </td>
            @foreach($verificator AS $item)
                <td style="width: 30%">
                    {{ ($item->verified_at == null) ? ' ' : 'Tertanda Tangan Secara Digital' }}
                    <br>{{ ($item->verified_at == null) ? ' ' : 'Pada: '.date('d-m-Y, H:i:s',strtotime($item->verified_at)) }}
                    <br>
                    <br><strong style="font-size: 13px">{{ ucfirst($item->verificator) }}</strong>
                </td>
            @endforeach
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
            <td class="text-bold text-setos" style="width: 30%; font-size: 10px">Department</td>
            <td class="text-bold text-setos" style="width: 40%; font-size: 10px">Digunakan Untuk</td>
            <td class="text-bold text-setos" style="width: 30%; font-size: 10px">Catatan</td>
        </tr>
        <tr>
            <td style="font-size: 14px; vertical-align: top">{{ $info->department ?? '-' }}</td>
            <td style="font-size: 14px">{{ $info->info_penggunaan }}</td>
            <td style="font-size: 14px; vertical-align: top">{{ $info->catatan ?? '-' }}</td>
        </tr>
    </table>
    <hr>
    <table class="table-transaksi">
        <thead>
        <tr class="text-center">
            <th class="bg-setos" style="color: white; width: 8%">No</th>
            <th class="bg-setos" style="color: white; width: 40%">Produk</th>
            <th class="bg-setos" style="color: white; width: 20%">Quantity</th>
            <th class="bg-setos" style="color: white; width: 20%">Harga (Rp)</th>
            <th class="bg-setos" style="color: white; width: 20%">Total (Rp)</th>
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
