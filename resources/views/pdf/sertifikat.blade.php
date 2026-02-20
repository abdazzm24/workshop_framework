<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Sertifikat Buku</title>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;500&display=swap" rel="stylesheet">

<style>

    /* WAJIB untuk print / DomPDF */
    @page {
        size: A4 landscape;
        margin: 0;
    }

    html, body {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Poppins', sans-serif;
    }

    .certificate {
        width: 100%;
        height: 100vh; /* Full 1 halaman */
        box-sizing: border-box;
        padding: 40px 80px;
        text-align: center;
        position: relative;
        background: url('{{ public_path('bg_sertifikat.png') }}') no-repeat center center;
        background-size: cover;
    }

    .logo {
        width: 70px;
        margin-bottom: 15px;
    }

    .title {
        font-family: 'Playfair Display', serif;
        font-size: 50px;
        letter-spacing: 6px;
        color: #4b2e83;
        margin: 10px 0;
    }

    .subtitle {
        font-size: 18px;
        margin-bottom: 20px;
    }

    .nama {
        font-family: 'Playfair Display', serif;
        font-size: 40px;
        font-weight: bold;
        margin: 15px 0;
        border-bottom: 2px solid #4b2e83;
        display: inline-block;
        padding-bottom: 5px;
    }

    .deskripsi {
        font-size: 18px;
        margin-top: 20px;
        line-height: 1.6;
    }

    .tanggal {
        margin-top: 25px;
        font-size: 16px;
    }

    .footer {
        position: absolute;
        bottom: 40px;
        left: 80px;
        right: 80px;
        display: flex;
        justify-content: space-between;
    }

    .ttd {
        width: 250px;
        text-align: center;
        position: relative;
    }

    .stempel {
        position: absolute;
        top: -20px;
        left: 60px;
        width: 110px;
        opacity: 0.6;
    }

    .garis-ttd {
        margin-top: 70px;
        border-top: 2px solid black;
        padding-top: 5px;
    }

</style>
</head>

<body>

<div class="certificate">

    <img src="{{ public_path('logo_unair.png') }}" class="logo">

    <div class="title">SERTIFIKAT</div>

    <div class="subtitle">
        Diberikan kepada:
    </div>

    <div class="nama">
        {{ $buku->judul }}
    </div>

    <div class="deskripsi">
        Atas kontribusinya sebagai buku dalam kategori 
        <strong>{{ $buku->kategori->nama_kategori }}</strong><br>
        yang ditulis oleh <strong>{{ $buku->pengarang }}</strong>.
    </div>

    <div class="tanggal">
        Surabaya, {{ date('d F Y') }}
    </div>

    <div class="footer">

        <div class="ttd">
            <img src="{{ public_path('stempel.png') }}" class="stempel">
            <div class="garis-ttd">
                <strong>Administrator Sistem</strong><br>
                Sistem Admin Buku
            </div>
        </div>

        <div class="ttd">
            <div class="garis-ttd">
                <strong>Dekan Fakultas Vokasi</strong><br>
                Universitas Airlangga
            </div>
        </div>

    </div>

</div>

</body>
</html>