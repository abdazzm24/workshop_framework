<!DOCTYPE html>
<html>
<head>
    <title>Surat Undangan</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            margin: 40px;
            font-size: 14px;
        }

        .kop-surat {
            text-align: center;
        }

        .kop-surat img {
            width: 80px;
            position: absolute;
            left: 50px;
            top: 40px;
        }

        .kop-surat h2, 
        .kop-surat h3, 
        .kop-surat p {
            margin: 2px;
        }

        .garis {
            border-top: 3px solid black;
            border-bottom: 1px solid black;
            height: 4px;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .isi-surat {
            margin-top: 20px;
            text-align: justify;
        }

        .ttd {
            margin-top: 60px;
            width: 300px;
            float: right;
            text-align: center;
            position: relative;
        }

        .stempel {
            position: absolute;
            top: 40px;
            left: 30px;
            width: 120px;
            opacity: 0.7;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <div class="kop-surat">
        <!-- Logo kiri -->
        <img src="{{ public_path('logo_unair.png') }}" alt="Logo">

        <h2>UNIVERSITAS AIRLANGGA</h2>
        <h3>FAKULTAS VOKASI</h3>
        <p>Kampus B Jl. Dharmawangsa Dalam Surabaya 60286</p>
        <p>http://vokasi.unair.ac.id | info@vokasi.unair.ac.id</p>
    </div>

    <div class="garis"></div>

    <!-- Nomor Surat -->
    <table width="100%">
        <tr>
            <td width="70%">
                Nomor &nbsp;&nbsp;&nbsp;: 001/ADM-BUKU/I/2026 <br>
                Lampiran : - <br>
                Perihal &nbsp;&nbsp;: Laporan Data Kategori
            </td>
            <td align="right">
                Surabaya, {{ date('d F Y') }}
            </td>
        </tr>
    </table>

    <div class="isi-surat">
        <p>
            Yth.<br>
            Pimpinan Sistem Admin Buku <br>
            di Tempat
        </p>

        <p>
            Dengan hormat, <br><br>
            Berikut kami sampaikan laporan data kategori pada Sistem Admin Buku.
            Adapun daftar kategori yang terdaftar dalam sistem adalah sebagai berikut:
        </p>

        <!-- TABEL DATA -->
        <table border="1" width="100%" cellspacing="0" cellpadding="6">
            <thead>
                <tr>
                    <th width="10%">No</th>
                    <th>Nama Kategori</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kategori as $index => $item)
                    <tr>
                        <td align="center">{{ $index + 1 }}</td>
                        <td>{{ $item->nama_kategori }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p style="margin-top:20px;">
            Demikian laporan ini kami sampaikan. Atas perhatian dan kerja samanya,
            kami ucapkan terima kasih.
        </p>
    </div>

    <!-- TANDA TANGAN -->
    <div class="ttd">
        <p>Hormat kami,</p>

        <!-- Stempel -->
        <img src="{{ public_path('stempel.png') }}" class="stempel" alt="Stempel">

        <br><br><br>
        <p><b>Administrator Sistem</b></p>
        <p>NIP. 1234567890</p>
    </div>

</body>
</html>