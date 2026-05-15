@extends('layouts.app')

@section('title', 'Tambah Toko')
@section('page-title', 'Tambah Toko Baru')
@section('icon', 'mdi mdi-store-plus')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('kunjungan.index') }}">Kunjungan Toko</a></li>
<li class="breadcrumb-item active">Tambah Toko</li>
@endsection

@section('content')

<div class="row justify-content-center">
<div class="col-md-7">
<div class="card shadow-sm border-0 rounded-4">
<div class="card-body p-4">

    <h5 class="fw-semibold mb-1">Tambah Lokasi Toko</h5>
    <p class="text-muted small mb-4">Tambahkan lokasi toko untuk validasi kunjungan sales</p>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kunjungan.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-medium">Barcode Toko</label>
            <input type="text" name="barcode"
                   class="form-control"
                   placeholder="Contoh: TK001 (maks 8 karakter)"
                   maxlength="8"
                   value="{{ old('barcode') }}"
                   required>
            <div class="form-text">Barcode unik untuk identifikasi toko (maks 8 karakter)</div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium">Nama Toko</label>
            <input type="text" name="nama_toko"
                   class="form-control"
                   placeholder="Masukkan nama toko"
                   maxlength="50"
                   value="{{ old('nama_toko') }}"
                   required>
        </div>

        <hr class="my-3">
        <p class="fw-medium mb-2">
            <i class="mdi mdi-map-marker text-primary"></i>
            Input Titik Awal (Lokasi Toko)
        </p>
        <p class="text-muted small mb-3">
            Klik tombol <strong>Ambil Lokasi</strong> saat berada di depan toko.
            Sistem akan mencari akurasi terbaik secara otomatis (seperti share location WhatsApp).
        </p>

        <div class="mb-3">
            <label class="form-label">Latitude</label>
            <input type="text" name="latitude" id="latitude"
                   class="form-control" placeholder="Otomatis dari GPS"
                   value="{{ old('latitude') }}" required readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Longitude</label>
            <input type="text" name="longitude" id="longitude"
                   class="form-control" placeholder="Otomatis dari GPS"
                   value="{{ old('longitude') }}" required readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Accuracy (meter)</label>
            <input type="text" name="accuracy" id="accuracy"
                   class="form-control" placeholder="Otomatis dari GPS"
                   value="{{ old('accuracy') }}" required readonly>
        </div>

        {{-- Status GPS --}}
        <div id="gpsStatus" class="alert alert-secondary py-2 small" style="display:none;"></div>

        <div class="d-flex gap-2 mb-4">
            <button type="button" id="btnGPS"
                    onclick="ambilLokasi()"
                    class="btn btn-gradient-info">
                <i class="mdi mdi-crosshairs-gps me-1"></i> Ambil Lokasi
            </button>
            <button type="button" id="btnBatalGPS"
                    onclick="batalGPS()"
                    class="btn btn-outline-secondary"
                    style="display:none;">
                Batalkan
            </button>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-gradient-primary">
                <i class="mdi mdi-content-save me-1"></i> Simpan Toko
            </button>
            <a href="{{ route('kunjungan.index') }}" class="btn btn-outline-secondary">
                Kembali
            </a>
        </div>

    </form>

</div>
</div>
</div>
</div>

@endsection

@section('js-page')
<script>
let watchId = null;

/**
 * Fungsi ambil lokasi akurat — seperti share location WhatsApp
 * Terus memantau GPS sampai akurasi ≤ targetAccuracy atau timeout
 */
function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) {
    return new Promise((resolve, reject) => {
        let bestResult = null;
        const startTime = Date.now();

        watchId = navigator.geolocation.watchPosition(
            (position) => {
                const acc = position.coords.accuracy;

                // Simpan hasil terbaik sejauh ini
                if (!bestResult || acc < bestResult.coords.accuracy) {
                    bestResult = position;

                    // Update UI realtime
                    document.getElementById('latitude').value  = position.coords.latitude.toFixed(8);
                    document.getElementById('longitude').value = position.coords.longitude.toFixed(8);
                    document.getElementById('accuracy').value  = acc.toFixed(2);
                    document.getElementById('gpsStatus').innerHTML =
                        `📡 Mencari akurasi terbaik... saat ini: <strong>${acc.toFixed(1)} m</strong>`;
                }

                // Sudah cukup akurat → selesai
                if (acc <= targetAccuracy) {
                    navigator.geolocation.clearWatch(watchId);
                    watchId = null;
                    resolve(bestResult);
                }

                // Timeout → pakai hasil terbaik yang ada
                if (Date.now() - startTime >= maxWait) {
                    navigator.geolocation.clearWatch(watchId);
                    watchId = null;
                    if (bestResult) resolve(bestResult);
                    else reject(new Error('Timeout: tidak dapat posisi GPS'));
                }
            },
            (error) => {
                watchId = null;
                reject(error);
            },
            { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait }
        );
    });
}

async function ambilLokasi() {
    if (!navigator.geolocation) {
        alert('Browser tidak mendukung Geolocation.');
        return;
    }

    document.getElementById('btnGPS').disabled = true;
    document.getElementById('btnBatalGPS').style.display = 'inline-block';
    document.getElementById('gpsStatus').style.display = 'block';
    document.getElementById('gpsStatus').className = 'alert alert-info py-2 small';
    document.getElementById('gpsStatus').innerHTML = '📡 Meminta izin GPS...';

    try {
        const pos = await getAccuratePosition(50, 20000);

        document.getElementById('latitude').value  = pos.coords.latitude.toFixed(8);
        document.getElementById('longitude').value = pos.coords.longitude.toFixed(8);
        document.getElementById('accuracy').value  = pos.coords.accuracy.toFixed(2);

        document.getElementById('gpsStatus').className = 'alert alert-success py-2 small';
        document.getElementById('gpsStatus').innerHTML =
            `✅ Lokasi berhasil diambil! Akurasi: <strong>${pos.coords.accuracy.toFixed(1)} m</strong>`;

    } catch (err) {
        document.getElementById('gpsStatus').className = 'alert alert-danger py-2 small';
        document.getElementById('gpsStatus').innerHTML = '❌ Gagal ambil lokasi: ' + err.message;
    }

    document.getElementById('btnGPS').disabled = false;
    document.getElementById('btnBatalGPS').style.display = 'none';
}

function batalGPS() {
    if (watchId !== null) {
        navigator.geolocation.clearWatch(watchId);
        watchId = null;
    }
    document.getElementById('btnGPS').disabled = false;
    document.getElementById('btnBatalGPS').style.display = 'none';
    document.getElementById('gpsStatus').className = 'alert alert-secondary py-2 small';
    document.getElementById('gpsStatus').innerHTML = '⏹️ Pencarian GPS dibatalkan.';
}
</script>
@endsection