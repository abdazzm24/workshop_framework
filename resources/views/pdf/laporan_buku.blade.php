<!DOCTYPE html>
<html>
<head>
    <title>Surat Laporan Data Buku</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            margin: 40px;
            font-size: 14px;
        }

        .kop {
            text-align: center;
        }

        .kop img {
            width: 80px;
            position: absolute;
            left: 50px;
            top: 40px;
        }

        .kop h2, .kop h3, .kop p {
            margin: 2px;
        }

        .garis {
            border-top: 3px solid black;
            border-bottom: 1px solid black;
            height: 4px;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .isi {
            margin-top: 20px;
            text-align: justify;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 6px;
            font-size: 13px;
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
            left: 40px;
            width: 120px;
            opacity: 0.6;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <div class="kop">
        <img src="{{ public_path('logo_unair.png') }}" alt="Logo">

        <h2>UNIVERSITAS AIRLANGGA</h2>
        <h3>FAKULTAS VOKASI</h3>
        <p>Kampus B Jl. Dharmawangsa Dalam Surabaya 60286</p>
        <p>http://vokasi.unair.ac.id | info@vokasi.unair.ac.id</p>
    </div>

    <div class="garis"></div>

    <!-- Nomor Surat -->
    <table width="100%" style="border: none;">
        <tr style="border: none;">
            <td width="70%" style="border: none;">
                Nomor &nbsp;&nbsp;&nbsp;: 002/ADM-BUKU/I/2026 <br>
                Lampiran : - <br>
                Perihal &nbsp;&nbsp;: Laporan Data Buku 2
            </td>
            <td align="right" style="border: none;">
                Surabaya, {{ date('d F Y') }}
            </td>
        </tr>
    </table>

    <!-- Isi Surat -->
    <div class="isi">
        <p>
            Yth.<br>
            Pimpinan Sistem Admin Buku <br>
            di Tempat
        </p>

        <p>
            Dengan hormat, <br><br>
            Berikut kami sampaikan laporan data buku yang terdaftar pada Sistem Admin Buku.
            Adapun rincian data buku adalah sebagai berikut:
        </p>

        <!-- TABEL DATA BUKU -->
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Kode</th>
                    <th width="30%">Judul</th>
                    <th width="20%">Kategori</th>
                    <th width="20%">Pengarang</th>
                </tr>
            </thead>
            <tbody>
                @foreach($buku as $index => $item)
                    <tr>
                        <td align="center">{{ $index + 1 }}</td>
                        <td>{{ $item->kode }}</td>
                        <td>{{ $item->judul }}</td>
                        <td>{{ $item->kategori->nama_kategori }}</td>
                        <td>{{ $item->pengarang }}</td>
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