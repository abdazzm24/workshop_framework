<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Label Harga</title>
<style>
@page {
    size: A4 portrait;
    margin: 4mm 5mm;
}

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.grid-container {
    width: 200mm;
    display: block;
}

.label-row {
    display: table;
    width: 100%;
    table-layout: fixed;
    margin: 0 0 2mm 0;
    padding: 0;
    border-collapse: separate;
    border-spacing: 2mm 0;
}

.label {
    display: table-cell;
    width: 38mm;
    height: 28mm;
    border: 1px dashed #ccc;
    text-align: center;
    vertical-align: middle;
    padding: 1mm;
    margin: 0;
    box-sizing: border-box;
}

.label-content {
    font-size: 7pt;
    line-height: 1.3;
}

.item-name {
    display: block;
    font-size: 8pt;
    font-weight: bold;
    margin-bottom: 1mm;
    color: #333;
}

.price-line {
    display: block;
    font-size: 9pt;
    margin-bottom: 1.5mm;
}

.price {
    font-weight: bold;
}

.barcode-img {
    width: 90%;
    height: auto;
    display: block;
    margin: 0 auto 0.5mm auto;
}

.barcode-code {
    font-size: 6pt;
    color: #555;
}
</style>
</head>
<body>

@php
use Picqer\Barcode\BarcodeGeneratorPNG;

// PNG generator — DomPDF support base64 PNG dengan sempurna
$generator   = new BarcodeGeneratorPNG();
$kolom       = 5;
$baris       = 8;
$dataIndex   = 0;
$startCol    = $x;
$startRow    = $y;
$startNumber = ($startRow - 1) * $kolom + $startCol;
@endphp

<div class="grid-container">

@for($row = 1; $row <= $baris; $row++)
    <div class="label-row">
    @for($col = 1; $col <= $kolom; $col++)
        @php $labelNumber = ($row - 1) * $kolom + $col; @endphp

        @if($labelNumber < $startNumber)
            <div class="label"></div>

        @elseif(isset($barang[$dataIndex]))
            @php
                // Generate PNG lalu encode base64 — cara yang sama seperti teman
                $png       = $generator->getBarcode(
                    $barang[$dataIndex]->id_barang,
                    $generator::TYPE_CODE_128,
                    2,    // width per bar
                    50    // height barcode
                );
                $base64 = base64_encode($png);
            @endphp

            <div class="label">
                <div class="label-content">
                    <span class="item-name">{{ $barang[$dataIndex]->nama }}</span>
                    <span class="price-line">
                        Rp <span class="price">{{ number_format($barang[$dataIndex]->harga, 0, ',', '.') }}</span>
                    </span>
                    {{-- Barcode PNG base64 — works di DomPDF --}}
                    <img class="barcode-img"
                         src="data:image/png;base64,{{ $base64 }}"
                         alt="{{ $barang[$dataIndex]->id_barang }}">
                    <span class="barcode-code">{{ $barang[$dataIndex]->id_barang }}</span>
                </div>
            </div>
            @php $dataIndex++; @endphp

        @else
            <div class="label"></div>
        @endif

    @endfor
    </div>
@endfor

</div>
</body>
</html>