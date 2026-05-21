<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Antrian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            padding: 40px 36px;
            max-width: 420px;
            width: 100%;
        }

        .judul {
            font-size: 2rem;
            font-weight: 800;
            color: #111827;
            margin-bottom: 4px;
        }

        .subjudul {
            color: #6b7280;
            font-size: 0.95rem;
            margin-bottom: 28px;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 1rem;
            border: 2px solid #e5e7eb;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        }

        .btn-daftar {
            background: #3b82f6;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            padding: 12px;
            width: 100%;
            margin-top: 8px;
            transition: background 0.2s;
        }

        .btn-daftar:hover {
            background: #1d4ed8;
            color: white;
        }
    </style>
</head>
<body>

<div class="card">

    <div class="judul">Daftar Antrian</div>
    <div class="subjudul">Masukkan nama untuk mendapatkan nomor antrian</div>

    @if($errors->any())
        <div class="alert alert-danger rounded-3 py-2 mb-3">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('antrian.daftar') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold" style="color:#374151;">Nama Lengkap</label>
            <input type="text"
                   name="nama"
                   class="form-control"
                   placeholder="Contoh: Budi Santoso"
                   value="{{ old('nama') }}"
                   autofocus
                   required>
        </div>

        <button type="submit" class="btn-daftar">
            Ambil Nomor Antrian
        </button>
    </form>

</div>

</body>
</html>