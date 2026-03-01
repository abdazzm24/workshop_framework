<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 10mm; }

        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            width: {{ 100 / 6 }}%;
            height: 115px;
            border: 1px solid #000;
            text-align: center;
            vertical-align: middle;
            font-size: 12px;
        }
    </style>
</head>
<body>

@php
    $kolom = 6;
    $baris = 6;
    $maksSlot = $kolom * $baris;

    $startX = $x - 1;
    $startY = $y - 1;
    $startIndex = ($startY * $kolom) + $startX;

    $totalBarang = count($barang);
    $currentIndex = 0;
@endphp


{{-- ================= HALAMAN PERTAMA ================= --}}
<table>
@for($i = 0; $i < $maksSlot; $i++)

    @if($i % $kolom == 0)
        <tr>
    @endif

    <td>
        @if($i >= $startIndex && $currentIndex < $totalBarang)
            <strong>{{ $barang[$currentIndex]->nama }}</strong><br>
            Rp {{ number_format($barang[$currentIndex]->harga) }}
            @php $currentIndex++; @endphp
        @endif
    </td>

    @if($i % $kolom == $kolom - 1)
        </tr>
    @endif

@endfor
</table>


{{-- ================= HALAMAN BERIKUTNYA ================= --}}
@while($currentIndex < $totalBarang)

<div style="page-break-after: always;"></div>

<table>
@for($i = 0; $i < $maksSlot; $i++)

    @if($i % $kolom == 0)
        <tr>
    @endif

    <td>
        @if($currentIndex < $totalBarang)
            <strong>{{ $barang[$currentIndex]->nama }}</strong><br>
            Rp {{ number_format($barang[$currentIndex]->harga) }}
            @php $currentIndex++; @endphp
        @endif
    </td>

    @if($i % $kolom == $kolom - 1)
        </tr>
    @endif

@endfor
</table>

@endwhile

</body>
</html>