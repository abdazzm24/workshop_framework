<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Antrian</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f6fa; }
        .navbar-brand { font-weight: 800; font-size: 1.3rem; }
        .stat-card {
            border: none;
            border-radius: 16px;
            padding: 20px 24px;
            color: white;
            font-weight: 600;
        }
        .stat-card.menunggu { background: linear-gradient(135deg, #f093fb, #f5576c); }
        .stat-card.dipanggil { background: linear-gradient(135deg, #3b82f6, #60a5fa); }
        .stat-card.terlambat { background: linear-gradient(135deg, #fa709a, #fee140); }
        .stat-card .angka { font-size: 2.5rem; font-weight: 900; line-height: 1; }
        .stat-card .label { font-size: 0.85rem; opacity: 0.9; margin-top: 4px; }

        .panel-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .panel-card .card-header {
            background: transparent;
            border-bottom: 2px solid #f0f0f0;
            font-weight: 700;
            padding: 16px 20px;
        }

        /* Dipanggil sekarang */
        .dipanggil-panel {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 16px;
            padding: 24px;
            color: white;
            text-align: center;
        }
        .dipanggil-panel .nomor {
            font-size: 5rem;
            font-weight: 900;
            line-height: 1;
        }
        .dipanggil-panel .nama {
            font-size: 1.3rem;
            opacity: 0.9;
            margin-top: 4px;
        }

        .btn-panggil {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            padding: 14px 30px;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-panggil:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59,130,246,0.4);
            color: white;
        }
        .btn-panggil:disabled {
            background: #ccc;
            transform: none;
            box-shadow: none;
        }

        .antrian-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 8px;
            background: #f8f9fa;
            transition: background 0.2s;
        }
        .antrian-item:hover { background: #e9ecef; }
        .antrian-item .nomor-badge {
            background: #3b82f6;
            color: white;
            border-radius: 8px;
            padding: 4px 12px;
            font-weight: 700;
            font-size: 1rem;
            min-width: 50px;
            text-align: center;
            margin-right: 12px;
        }
        .antrian-item .nama-text {
            flex: 1;
            font-weight: 500;
        }
        .btn-terlambat {
            border: 1px solid #dc3545;
            color: #dc3545;
            border-radius: 8px;
            padding: 3px 12px;
            font-size: 0.8rem;
            background: transparent;
            transition: all 0.2s;
        }
        .btn-terlambat:hover {
            background: #dc3545;
            color: white;
        }

        .terlambat-item {
            display: flex;
            align-items: center;
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 6px;
            background: #fff8f0;
            border: 1px solid #ffd9a0;
            cursor: pointer;
            transition: background 0.2s;
        }
        .terlambat-item:hover { background: #fff0d9; }
        .terlambat-item .nomor-badge {
            background: #fd7e14;
            color: white;
            border-radius: 8px;
            padding: 4px 10px;
            font-weight: 700;
            font-size: 0.9rem;
            min-width: 46px;
            text-align: center;
            margin-right: 10px;
        }
        .terlambat-hint {
            font-size: 0.75rem;
            color: #999;
            margin-top: 6px;
        }

        .empty-state {
            text-align: center;
            color: #bbb;
            padding: 30px 0;
            font-size: 0.95rem;
        }

        /* Notifikasi toast */
        .toast-container { z-index: 9999; }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav class="navbar navbar-dark" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
    <div class="container-fluid px-4">
        <span class="navbar-brand">🏥 Admin Antrian</span>
        <span class="text-white-50 small" id="waktu-sekarang"></span>
    </div>
</nav>

<div class="container-fluid px-4 py-4">

    {{-- STAT CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card menunggu">
                <div class="angka" id="stat-menunggu">{{ $menunggu->count() }}</div>
                <div class="label">⏳ Menunggu</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card dipanggil">
                <div class="angka" id="stat-dipanggil">{{ $dipanggil ? 1 : 0 }}</div>
                <div class="label">📢 Sedang Dipanggil</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card terlambat">
                <div class="angka" id="stat-terlambat">{{ $terlambat->count() }}</div>
                <div class="label">⚠️ Terlambat</div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- PANEL KIRI: Panggil + Sekarang Dipanggil --}}
        <div class="col-lg-4">

            {{-- Dipanggil Sekarang --}}
            <div class="panel-card card mb-4">
                <div class="card-header">📢 Sedang Dipanggil</div>
                <div class="card-body p-3">
                    <div id="panel-dipanggil">
                        @if($dipanggil)
                            <div class="dipanggil-panel">
                                <div class="nomor">{{ str_pad($dipanggil->nomor, 3, '0', STR_PAD_LEFT) }}</div>
                                <div class="nama">{{ $dipanggil->nama }}</div>
                            </div>
                        @else
                            <div class="empty-state">Belum ada yang dipanggil</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Tombol Panggil --}}
            <button id="btn-panggil" onclick="panggilBerikutnya()" class="btn-panggil mb-3"
                    {{ $menunggu->count() === 0 ? 'disabled' : '' }}>
                📢 Panggil Berikutnya
            </button>

        </div>

        {{-- PANEL TENGAH: Daftar Menunggu --}}
        <div class="col-lg-4">
            <div class="panel-card card h-100">
                <div class="card-header">
                    ⏳ Daftar Menunggu
                    <span class="badge bg-danger ms-1" id="badge-menunggu">{{ $menunggu->count() }}</span>
                </div>
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    <div id="list-menunggu">
                        @forelse($menunggu as $item)
                            <div class="antrian-item" id="item-{{ $item->id }}">
                                <span class="nomor-badge">{{ str_pad($item->nomor, 3, '0', STR_PAD_LEFT) }}</span>
                                <span class="nama-text">{{ $item->nama }}</span>
                                <button class="btn-terlambat"
                                        onclick="tandaiTerlambat({{ $item->id }}, this)">
                                    Terlambat
                                </button>
                            </div>
                        @empty
                            <div class="empty-state" id="empty-menunggu">Antrian kosong</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- PANEL KANAN: Daftar Terlambat --}}
        <div class="col-lg-4">
            <div class="panel-card card h-100">
                <div class="card-header">
                    ⚠️ Terlambat / Tidak Hadir
                    <span class="badge bg-warning text-dark ms-1" id="badge-terlambat">{{ $terlambat->count() }}</span>
                </div>
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    <p class="terlambat-hint">💡 Double-klik nama untuk memanggil ulang</p>
                    <div id="list-terlambat">
                        @forelse($terlambat as $item)
                            <div class="terlambat-item"
                                 ondblclick="panggilTerlambat({{ $item->id }}, '{{ $item->nama }}', {{ $item->nomor }})"
                                 title="Double-klik untuk panggil ulang">
                                <span class="nomor-badge">{{ str_pad($item->nomor, 3, '0', STR_PAD_LEFT) }}</span>
                                <span class="nama-text">{{ $item->nama }}</span>
                                <small class="text-muted">↩️</small>
                            </div>
                        @empty
                            <div class="empty-state" id="empty-terlambat">Tidak ada yang terlambat</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Toast Notifikasi --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="toast-notif" class="toast align-items-center border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body fw-semibold" id="toast-pesan"></div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// ─── JAM SEKARANG ─────────────────────────────────────────────────────────────
function updateJam() {
    const now = new Date();
    document.getElementById('waktu-sekarang').textContent =
        now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
}
setInterval(updateJam, 1000);
updateJam();

// ─── SSE: Dengarkan update dari server ────────────────────────────────────────
const source = new EventSource('{{ route("antrian.stream") }}');

source.addEventListener('queue-update', function(e) {
    const data = JSON.parse(e.data);
    updateUI(data);
});

source.onerror = function() {
    // Browser otomatis reconnect — tidak perlu handle manual
    console.log('SSE reconnecting...');
};

function updateUI(data) {
    // Update stat angka
    document.getElementById('stat-menunggu').textContent   = data.total_menunggu;
    document.getElementById('stat-terlambat').textContent  = data.terlambat.length;
    document.getElementById('stat-dipanggil').textContent  = data.dipanggil ? 1 : 0;

    // Update badge
    document.getElementById('badge-menunggu').textContent  = data.total_menunggu;
    document.getElementById('badge-terlambat').textContent = data.terlambat.length;

    // Update panel dipanggil
    const panelDipanggil = document.getElementById('panel-dipanggil');
    if (data.dipanggil) {
        const nomorPad = String(data.dipanggil.nomor).padStart(3, '0');
        panelDipanggil.innerHTML = `
            <div class="dipanggil-panel">
                <div class="nomor">${nomorPad}</div>
                <div class="nama">${data.dipanggil.nama}</div>
            </div>`;
    } else {
        panelDipanggil.innerHTML = '<div class="empty-state">Belum ada yang dipanggil</div>';
    }

    // Update list menunggu
    const listMenunggu = document.getElementById('list-menunggu');
    if (data.menunggu.length === 0) {
        listMenunggu.innerHTML = '<div class="empty-state">Antrian kosong</div>';
        document.getElementById('btn-panggil').disabled = true;
    } else {
        document.getElementById('btn-panggil').disabled = false;
        listMenunggu.innerHTML = data.menunggu.map(item => {
            const nPad = String(item.nomor).padStart(3, '0');
            return `
            <div class="antrian-item" id="item-${item.id}">
                <span class="nomor-badge">${nPad}</span>
                <span class="nama-text">${item.nama}</span>
                <button class="btn-terlambat"
                        onclick="tandaiTerlambat(${item.id}, this)">
                    Terlambat
                </button>
            </div>`;
        }).join('');
    }

    // Update list terlambat
    const listTerlambat = document.getElementById('list-terlambat');
    if (data.terlambat.length === 0) {
        listTerlambat.innerHTML = '<div class="empty-state">Tidak ada yang terlambat</div>';
    } else {
        listTerlambat.innerHTML = data.terlambat.map(item => {
            const nPad = String(item.nomor).padStart(3, '0');
            return `
            <div class="terlambat-item"
                 ondblclick="panggilTerlambat(${item.id}, '${item.nama}', ${item.nomor})"
                 title="Double-klik untuk panggil ulang">
                <span class="nomor-badge">${nPad}</span>
                <span class="nama-text">${item.nama}</span>
                <small class="text-muted">↩️</small>
            </div>`;
        }).join('');
    }
}

// ─── PANGGIL BERIKUTNYA ───────────────────────────────────────────────────────
function panggilBerikutnya() {
    document.getElementById('btn-panggil').disabled = true;

    fetch('{{ route("antrian.panggil") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({})
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) {
            tampilToast('⚠️ ' + data.error, 'bg-warning text-dark');
            document.getElementById('btn-panggil').disabled = false;
        } else {
            tampilToast(`📢 Memanggil nomor ${String(data.nomor).padStart(3,'0')} — ${data.nama}`, 'bg-success text-white');
        }
        // UI akan diupdate otomatis via SSE
    })
    .catch(() => {
        tampilToast('❌ Gagal memanggil. Coba lagi.', 'bg-danger text-white');
        document.getElementById('btn-panggil').disabled = false;
    });
}

// ─── TANDAI TERLAMBAT ─────────────────────────────────────────────────────────
function tandaiTerlambat(id, btn) {
    if (!confirm('Tandai sebagai terlambat/tidak hadir?')) return;
    btn.disabled = true;

    fetch('{{ route("antrian.terlambat") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ id })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            tampilToast('⚠️ Ditandai terlambat', 'bg-warning text-dark');
        }
    });
}

// ─── PANGGIL TERLAMBAT (double-click) ─────────────────────────────────────────
function panggilTerlambat(id, nama, nomor) {
    if (!confirm(`Panggil ulang nomor ${String(nomor).padStart(3,'0')} — ${nama}?`)) return;

    fetch('{{ route("antrian.panggilTerlambat") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ id })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            tampilToast(`📢 Memanggil ulang ${String(data.nomor).padStart(3,'0')} — ${data.nama}`, 'bg-info text-white');
        }
    });
}

// ─── TOAST HELPER ─────────────────────────────────────────────────────────────
function tampilToast(pesan, kelas = 'bg-success text-white') {
    const el = document.getElementById('toast-notif');
    el.className = `toast align-items-center border-0 ${kelas}`;
    document.getElementById('toast-pesan').textContent = pesan;
    new bootstrap.Toast(el, { delay: 3000 }).show();
}
</script>
</body>
</html>