<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Barcode Toko - {{ $toko->nama_toko }}</title>
<style>
    body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
    .label {
        display: inline-block;
        border: 1px dashed #ccc;
        padding: 15px 20px;
        border-radius: 8px;
        margin: 10px;
    }
    .nama { font-size: 14pt; font-weight: bold; margin-bottom: 5px; }
    .barcode-img { display: block; margin: 8px auto; }
    .kode { font-size: 9pt; color: #555; }
    @media print { button { display: none; } }
</style>
</head>
<body>

<div class="label">
    <div class="nama">{{ $toko->nama_toko }}</div>
    <img class="barcode-img"
         src="data:image/png;base64,{{ $base64 }}"
         alt="{{ $toko->barcode }}"
         width="200">
    <div class="kode">{{ $toko->barcode }}</div>
</div>

<br>
<button onclick="window.print()" style="margin:10px; padding:8px 20px; cursor:pointer;">
    🖨️ Print
</button>
<button onclick="window.close()" style="margin:10px; padding:8px 20px; cursor:pointer;">
    Tutup
</button>

</body>
</html>