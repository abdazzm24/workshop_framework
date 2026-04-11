<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Pembayaran</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 12px;
        }

        .center {
            text-align: center;
        }

        .divider {
            border-top: 1px dashed #333;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            font-size: 10px;
            padding: 3px 0;
            text-align: left;
            vertical-align: top;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .header h2 {
            margin: 0;
        }

        .small {
            font-size: 10px;
            color: #555;
        }

        .barcode {
            text-align: center;
            margin: 10px 0;
        }

        .barcode img {
            width: 180px;
            height: auto;
        }

    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="center header">
        <h2>KANTIN APP</h2>
        <div class="small">Struk Pembayaran</div>
    </div>

    <div class="divider"></div>

    {{-- BARCODE --}}
    <div class="barcode">
        <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode">
    </div>

    {{-- INFO --}}
    <p>ID: {{ $pesanan->idpesanan }}</p>
    <p>Nama: {{ $pesanan->nama }}</p>
    <p>Tanggal: {{ \Carbon\Carbon::parse($pesanan->tanggal)->format('d/m/Y H:i') }}</p>

    <div class="divider"></div>

    {{-- DETAIL PESANAN --}}
    <table>
        @foreach($pesanan->detailPesanan as $item)
            @if($item->menu && $item->menu->vendor_id == auth()->user()->vendor->id)
            <tr>
                <td colspan="2">{{ $item->menu->nama_menu }}</td>
            </tr>
            <tr>
                <td class="small">
                    {{ $item->jumlah }} x {{ number_format($item->harga, 0, ',', '.') }}
                </td>
                <td class="right">
                    {{ number_format($item->subtotal, 0, ',', '.') }}
                </td>
            </tr>
            @endif
        @endforeach
    </table>

    <div class="divider"></div>

    {{-- TOTAL --}}
    <table>
        <tr>
            <td class="bold">TOTAL</td>
            <td class="right bold">
                Rp {{ number_format($pesanan->total, 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <div class="barcode">
        <img src="data:image/png;base64,{{ $qrcode }}">
    </div>

    <div class="divider"></div>

    
    {{-- FOOTER --}}
    <p class="center">
        Terima kasih 🙏 <br>
        Pesanan diproses
    </p>

    <div class="divider"></div>
    
    @if($pesanan->customer && $pesanan->customer->foto_blob)
        <div class="center">
            <img src="{{ $pesanan->customer->foto_blob }}" style="width:230px; height:auto; margin: 15px;">
        </div>
    @endif

</body>
</html>