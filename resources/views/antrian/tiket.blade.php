<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nomor Antrian #{{ $antrian->nomor }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tiket {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            overflow: hidden;
            max-width: 360px;
            width: 100%;
        }

        .tiket-header {
            background: #3b82f6;
            padding: 20px;
            text-align: center;
            color: white;
        }

        .tiket-header p {
            margin: 0;
            font-size: 0.85rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            opacity: 0.9;
        }

        .tiket-body {
            padding: 36px 24px;
            text-align: center;
        }

        .nomor {
            font-size: 8rem;
            font-weight: 900;
            color: #3b82f6;
            line-height: 1;
        }

        .nama {
            font-size: 1.4rem;
            font-weight: 600;
            color: #111827;
            margin-top: 8px;
        }

        .waktu {
            color: #9ca3af;
            font-size: 0.9rem;
            margin-top: 4px;
        }

        .tiket-footer {
            border-top: 2px dashed #e5e7eb;
            padding: 16px 24px;
            text-align: center;
            background: #f9fafb;
        }

        .status {
            display: inline-block;
            background: #fef9c3;
            color: #854d0e;
            border-radius: 20px;
            padding: 4px 18px;
            font-size: 0.85rem;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="tiket">

    <div class="tiket-header">
        <p>Nomor Antrian</p>
    </div>

    <div class="tiket-body">
        <div class="nomor">{{ str_pad($antrian->nomor, 3, '0', STR_PAD_LEFT) }}</div>
        <div class="nama">{{ $antrian->nama }}</div>
        <div class="waktu">
            Daftar pukul {{ \Carbon\Carbon::parse($antrian->created_at)->format('H:i') }} WIB
        </div>
    </div>

    <div class="tiket-footer">
        <div class="status">Menunggu Giliran</div>
        <p class="text-muted small mt-2 mb-0">
            Perhatikan papan antrian dan tunggu nomor Anda dipanggil
        </p>
    </div>

</div>

</body>
</html>