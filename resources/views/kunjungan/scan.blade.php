@extends('layouts.app')

@section('title', 'Scan Kunjungan')
@section('page-title', 'Scan Kunjungan Toko')
@section('icon', 'mdi mdi-map-marker-radius')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('kunjungan.index') }}">Kunjungan Toko</a></li>
<li class="breadcrumb-item active">Scan Kunjungan</li>
@endsection

@section('content')

<div class="row justify-content-center">
<div class="col-md-8">

    {{-- STEP 1: SCANNER ─────────────────────────────────────────────────────── --}}
    <div id="stepScan" class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 text-center">

            <h5 class="fw-semibold mb-1">Titik Kunjungan</h5>
            <p class="text-muted small mb-3">Scan barcode toko untuk memulai validasi kunjungan</p>

            {{-- Area Kamera --}}
            <div id="reader" style="width:100%; border-radius:12px; overflow:hidden;"></div>

            <div id="statusScan" class="alert alert-info mt-3 py-2 small" style="display:none;"></div>

            <div class="mt-3">
                <button id="btnMulai" onclick="mulaiScan()" class="btn btn-gradient-primary btn-sm px-4">
                    <i class="mdi mdi-barcode-scan me-1"></i> Mulai Scan
                </button>
                <button id="btnStop" onclick="stopScan()" class="btn btn-gradient-danger btn-sm px-4" style="display:none;">
                    <i class="mdi mdi-stop me-1"></i> Stop
                </button>
            </div>

        </div>
    </div>

    {{-- STEP 2: DATA TOKO + AMBIL LOKASI ─────────────────────────────────────── --}}
    <div id="stepLokasi" class="card shadow-sm border-0 rounded-4 mt-4" style="display:none;">
        <div class="card-body p-4">

            <h5 class="fw-semibold mb-3 text-primary">🏪 Toko Ditemukan</h5>

            <table class="table table-bordered table-sm mb-4">
                <tr>
                    <td class="fw-medium" style="width:40%">Barcode</td>
                    <td id="info-barcode"></td>
                </tr>
                <tr>
                    <td class="fw-medium">Nama Toko</td>
                    <td id="info-nama"></td>
                </tr>
                <tr>
                    <td class="fw-medium">Latitude Toko</td>
                    <td id="info-lat"></td>
                </tr>
                <tr>
                    <td class="fw-medium">Longitude Toko</td>
                    <td id="info-lng"></td>
                </tr>
                <tr>
                    <td class="fw-medium">Accuracy Toko</td>
                    <td><span id="info-acc" class="badge bg-info text-white"></span></td>
                </tr>
            </table>

            <hr>

            <p class="fw-medium mb-2">
                <i class="mdi mdi-crosshairs-gps text-success"></i>
                Ambil Lokasi Sales Saat Ini
            </p>
            <p class="text-muted small mb-3">
                Klik <strong>Ambil Lokasi</strong> untuk mengambil posisi GPS kamu sekarang.
                Sistem mencari akurasi terbaik secara otomatis.
            </p>

            <div id="gpsStatusSales" class="alert alert-secondary py-2 small mb-3" style="display:none;"></div>

            <div class="row mb-3">
                <div class="col-4">
                    <label class="form-label small">Latitude Sales</label>
                    <input type="text" id="sales-lat" class="form-control form-control-sm" readonly placeholder="—">
                </div>
                <div class="col-4">
                    <label class="form-label small">Longitude Sales</label>
                    <input type="text" id="sales-lng" class="form-control form-control-sm" readonly placeholder="—">
                </div>
                <div class="col-4">
                    <label class="form-label small">Accuracy Sales</label>
                    <input type="text" id="sales-acc" class="form-control form-control-sm" readonly placeholder="—">
                </div>
            </div>

            <div class="d-flex gap-2 mb-3">
                <button type="button" id="btnAmbilLokasi"
                        onclick="ambilLokasiSales()"
                        class="btn btn-gradient-success">
                    <i class="mdi mdi-crosshairs-gps me-1"></i> Ambil Lokasi
                </button>
                <button type="button" id="btnBatalGPS"
                        onclick="batalGPS()"
                        class="btn btn-outline-secondary"
                        style="display:none;">
                    Batalkan
                </button>
            </div>

            <button type="button" id="btnValidasi"
                    onclick="validasiKunjungan()"
                    class="btn btn-gradient-primary w-100"
                    disabled>
                <i class="mdi mdi-check-circle me-1"></i> Validasi Kunjungan
            </button>

        </div>
    </div>

    {{-- STEP 3: HASIL VALIDASI ───────────────────────────────────────────────── --}}
    <div id="stepHasil" class="card shadow-sm border-0 rounded-4 mt-4" style="display:none;">
        <div class="card-body p-4">

            <h5 class="fw-semibold mb-3">Hasil Validasi Kunjungan</h5>

            <div class="text-center mb-3">
                <span id="badge-status" class="badge fs-5 px-4 py-2"></span>
            </div>

            <table class="table table-bordered table-sm">
                <tr>
                    <td>Jarak Aktual</td>
                    <td><strong id="hasil-jarak"></strong></td>
                </tr>
                <tr>
                    <td>Accuracy Toko</td>
                    <td id="hasil-acc-toko"></td>
                </tr>
                <tr>
                    <td>Accuracy Sales</td>
                    <td id="hasil-acc-sales"></td>
                </tr>
                <tr>
                    <td>Threshold Dasar</td>
                    <td id="hasil-threshold-dasar"></td>
                </tr>
                <tr>
                    <td>Threshold Efektif</td>
                    <td><strong id="hasil-threshold-efektif"></strong></td>
                </tr>
            </table>

            <div class="d-flex gap-2 mt-3">
                <button onclick="scanLagi()" class="btn btn-gradient-primary">
                    <i class="mdi mdi-refresh me-1"></i> Scan Lagi
                </button>
                <a href="{{ route('kunjungan.index') }}" class="btn btn-outline-secondary">
                    Kembali ke List Toko
                </a>
            </div>

        </div>
    </div>

    {{-- ERROR ───────────────────────────────────────────────────────────────── --}}
    <div id="errorScan" class="alert alert-danger mt-3 rounded-3" style="display:none;">
        ❌ Toko tidak ditemukan! Barcode: <strong id="kode-error"></strong>
    </div>

</div>
</div>

@endsection

<!-- html5-qrcode -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<audio id="beep" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>

@section('js-page')
<script>
let html5QrCode = null;
let sudahScan   = false;
let watchId     = null;
let dataTokoScan = null; // simpan data toko hasil scan

// ─── SCANNER ──────────────────────────────────────────────────────────────────

function mulaiScan() {
    sudahScan = false;
    document.getElementById('btnMulai').style.display   = 'none';
    document.getElementById('btnStop').style.display    = 'inline-block';
    document.getElementById('errorScan').style.display  = 'none';
    document.getElementById('statusScan').style.display = 'block';
    document.getElementById('statusScan').innerText     = '📷 Kamera aktif... arahkan ke barcode toko';

    html5QrCode = new Html5Qrcode("reader");

    html5QrCode.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: { width: 250, height: 120 },
            formatsToSupport: [
                Html5QrcodeSupportedFormats.CODE_128,
                Html5QrcodeSupportedFormats.EAN_13,
                Html5QrcodeSupportedFormats.EAN_8,
                Html5QrcodeSupportedFormats.QR_CODE,
            ]
        },
        function(decodedText) {
            if (sudahScan) return;
            sudahScan = true;

            bunyikanBeep();
            stopScan();
            cariToko(decodedText);
        },
        function() { /* frame failure — diabaikan */ }
    ).catch(err => {
        alert('Kamera tidak bisa diakses: ' + err);
        document.getElementById('btnMulai').style.display = 'inline-block';
        document.getElementById('btnStop').style.display  = 'none';
    });
}

function stopScan() {
    if (html5QrCode) {
        html5QrCode.stop().then(() => html5QrCode.clear()).catch(() => {});
    }
    document.getElementById('btnMulai').style.display   = 'inline-block';
    document.getElementById('btnStop').style.display    = 'none';
    document.getElementById('statusScan').style.display = 'none';
}

// ─── CARI TOKO KE API ─────────────────────────────────────────────────────────

function cariToko(barcode) {
    fetch('/api/toko/' + barcode)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                document.getElementById('kode-error').innerText = barcode;
                document.getElementById('errorScan').style.display = 'block';
                return;
            }

            // Simpan data toko untuk dipakai saat validasi
            dataTokoScan = data;

            // Tampilkan info toko
            document.getElementById('info-barcode').innerText = data.barcode;
            document.getElementById('info-nama').innerText    = data.nama_toko;
            document.getElementById('info-lat').innerText     = data.latitude;
            document.getElementById('info-lng').innerText     = data.longitude;
            document.getElementById('info-acc').innerText     = data.accuracy + ' m';

            // Tampilkan step lokasi
            document.getElementById('stepLokasi').style.display = 'block';
            document.getElementById('stepLokasi').scrollIntoView({ behavior: 'smooth' });
        })
        .catch(() => {
            document.getElementById('kode-error').innerText = barcode;
            document.getElementById('errorScan').style.display = 'block';
        });
}

// ─── AMBIL LOKASI SALES (AKURAT) ──────────────────────────────────────────────

function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) {
    return new Promise((resolve, reject) => {
        let bestResult = null;
        const startTime = Date.now();

        watchId = navigator.geolocation.watchPosition(
            (position) => {
                const acc = position.coords.accuracy;

                if (!bestResult || acc < bestResult.coords.accuracy) {
                    bestResult = position;

                    // Update field realtime
                    document.getElementById('sales-lat').value = position.coords.latitude.toFixed(8);
                    document.getElementById('sales-lng').value = position.coords.longitude.toFixed(8);
                    document.getElementById('sales-acc').value = acc.toFixed(2);

                    document.getElementById('gpsStatusSales').style.display = 'block';
                    document.getElementById('gpsStatusSales').className = 'alert alert-info py-2 small mb-3';
                    document.getElementById('gpsStatusSales').innerHTML =
                        `📡 Mencari akurasi terbaik... saat ini: <strong>${acc.toFixed(1)} m</strong>`;
                }

                if (acc <= targetAccuracy) {
                    navigator.geolocation.clearWatch(watchId);
                    watchId = null;
                    resolve(bestResult);
                }

                if (Date.now() - startTime >= maxWait) {
                    navigator.geolocation.clearWatch(watchId);
                    watchId = null;
                    if (bestResult) resolve(bestResult);
                    else reject(new Error('Timeout: tidak dapat posisi GPS'));
                }
            },
            (error) => { watchId = null; reject(error); },
            { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait }
        );
    });
}

async function ambilLokasiSales() {
    if (!navigator.geolocation) {
        alert('Browser tidak mendukung Geolocation.');
        return;
    }

    document.getElementById('btnAmbilLokasi').disabled = true;
    document.getElementById('btnBatalGPS').style.display = 'inline-block';
    document.getElementById('btnValidasi').disabled = true;

    try {
        const pos = await getAccuratePosition(50, 20000);

        document.getElementById('sales-lat').value = pos.coords.latitude.toFixed(8);
        document.getElementById('sales-lng').value = pos.coords.longitude.toFixed(8);
        document.getElementById('sales-acc').value = pos.coords.accuracy.toFixed(2);

        document.getElementById('gpsStatusSales').className = 'alert alert-success py-2 small mb-3';
        document.getElementById('gpsStatusSales').innerHTML =
            `✅ Lokasi berhasil! Akurasi: <strong>${pos.coords.accuracy.toFixed(1)} m</strong>`;

        // Aktifkan tombol validasi
        document.getElementById('btnValidasi').disabled = false;

    } catch (err) {
        document.getElementById('gpsStatusSales').className = 'alert alert-danger py-2 small mb-3';
        document.getElementById('gpsStatusSales').innerHTML = '❌ Gagal: ' + err.message;
    }

    document.getElementById('btnAmbilLokasi').disabled = false;
    document.getElementById('btnBatalGPS').style.display = 'none';
}

function batalGPS() {
    if (watchId !== null) {
        navigator.geolocation.clearWatch(watchId);
        watchId = null;
    }
    document.getElementById('btnAmbilLokasi').disabled = false;
    document.getElementById('btnBatalGPS').style.display = 'none';
    document.getElementById('gpsStatusSales').className = 'alert alert-secondary py-2 small mb-3';
    document.getElementById('gpsStatusSales').innerHTML = '⏹️ Pencarian GPS dibatalkan.';
}

// ─── VALIDASI KUNJUNGAN ────────────────────────────────────────────────────────

function validasiKunjungan() {
    const latSales = document.getElementById('sales-lat').value;
    const lngSales = document.getElementById('sales-lng').value;
    const accSales = document.getElementById('sales-acc').value;

    if (!latSales || !lngSales || !accSales) {
        alert('Ambil lokasi terlebih dahulu!');
        return;
    }

    document.getElementById('btnValidasi').disabled = true;
    document.getElementById('btnValidasi').innerText = 'Memvalidasi...';

    fetch('/api/kunjungan/simpan', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            barcode_toko:   dataTokoScan.barcode,
            lat_sales:      parseFloat(latSales),
            lng_sales:      parseFloat(lngSales),
            accuracy_sales: parseFloat(accSales),
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            alert('Error: ' + data.error);
            return;
        }

        // Tampilkan hasil
        const isValid = data.status === 'VALID';
        document.getElementById('badge-status').textContent  = isValid ? '✅ VALID' : '❌ DITOLAK';
        document.getElementById('badge-status').className    = 'badge fs-5 px-4 py-2 ' + (isValid ? 'bg-success' : 'bg-danger');
        document.getElementById('hasil-jarak').textContent   = data.jarak_aktual + ' meter';
        document.getElementById('hasil-acc-toko').textContent    = data.accuracy_toko + ' meter';
        document.getElementById('hasil-acc-sales').textContent   = data.accuracy_sales + ' meter';
        document.getElementById('hasil-threshold-dasar').textContent    = data.threshold_dasar + ' meter';
        document.getElementById('hasil-threshold-efektif').textContent  = data.threshold_efektif + ' meter';

        document.getElementById('stepHasil').style.display = 'block';
        document.getElementById('stepHasil').scrollIntoView({ behavior: 'smooth' });
    })
    .catch(() => {
        alert('Gagal terhubung ke server. Coba lagi.');
        document.getElementById('btnValidasi').disabled = false;
        document.getElementById('btnValidasi').innerHTML = '<i class="mdi mdi-check-circle me-1"></i> Validasi Kunjungan';
    });
}

// ─── RESET ────────────────────────────────────────────────────────────────────

function scanLagi() {
    dataTokoScan = null;
    sudahScan    = false;

    document.getElementById('stepLokasi').style.display  = 'none';
    document.getElementById('stepHasil').style.display   = 'none';
    document.getElementById('errorScan').style.display   = 'none';
    document.getElementById('gpsStatusSales').style.display = 'none';
    document.getElementById('btnValidasi').disabled      = true;
    document.getElementById('btnValidasi').innerHTML     = '<i class="mdi mdi-check-circle me-1"></i> Validasi Kunjungan';

    document.getElementById('sales-lat').value = '';
    document.getElementById('sales-lng').value = '';
    document.getElementById('sales-acc').value = '';

    window.scrollTo({ top: 0, behavior: 'smooth' });
    mulaiScan();
}

// ─── BEEP ─────────────────────────────────────────────────────────────────────

function bunyikanBeep() {
    const beep = document.getElementById('beep');
    if (beep) {
        beep.currentTime = 0;
        beep.play().catch(() => {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.value = 1000;
            gain.gain.value = 0.3;
            osc.start();
            osc.stop(ctx.currentTime + 0.15);
        });
    }
}
</script>
@endsection