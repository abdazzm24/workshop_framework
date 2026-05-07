@extends('layouts.app')

@section('title', 'Scan Barcode')
@section('page-title', 'Scan Barcode Barang')
@section('icon', 'mdi mdi-barcode-scan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Scan Barcode</li>
@endsection

@section('content')

<div class="row">
<div class="col-lg-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="card-title mb-0">Scanner Barcode Barang</h4>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body text-center p-4">

                    <!-- Area Kamera -->
                    <div id="reader" style="
                        width: 100%;
                        border-radius: 12px;
                        overflow: hidden;
                    "></div>

                    <p class="text-muted mt-2 small">Arahkan kamera ke barcode barang</p>

                    <!-- Status -->
                    <div id="statusScan" class="alert alert-info mt-2 py-2 small" style="display:none;"></div>

                    <!-- Tombol -->
                    <button id="btnMulai" onclick="mulaiScan()" class="btn btn-gradient-primary btn-sm mt-2 px-4">
                        <i class="mdi mdi-barcode-scan me-1"></i> Mulai Scan
                    </button>
                    <button id="btnStop" onclick="stopScan()" class="btn btn-gradient-danger btn-sm mt-2 px-4" style="display:none;">
                        <i class="mdi mdi-stop me-1"></i> Stop
                    </button>

                </div>
            </div>

            <!-- Hasil Scan -->
            <div id="hasilScan" class="card shadow-sm border-0 rounded-4 mt-4" style="display:none;">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-3 text-success">✅ Barang Ditemukan</h5>
                    <table class="table table-bordered rounded-3">
                        <tr>
                            <td class="fw-medium" style="width:40%">ID Barang</td>
                            <td id="res-id"></td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Nama Barang</td>
                            <td id="res-nama"></td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Harga</td>
                            <td id="res-harga"></td>
                        </tr>
                    </table>
                    <button onclick="scanLagi()" class="btn btn-gradient-primary btn-sm w-100 mt-2">
                        <i class="mdi mdi-refresh me-1"></i> Scan Lagi
                    </button>
                </div>
            </div>

            <!-- Error -->
            <div id="errorScan" class="alert alert-danger mt-3 rounded-3" style="display:none;">
                ❌ Barang tidak ditemukan! Kode: <strong id="kode-tidak-ditemukan"></strong>
            </div>

        </div>
    </div>

</div>
</div>
</div>
</div>

@endsection

<!-- Library html5-qrcode -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<!-- Suara Beep -->
<audio id="beep" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>

@section('js-page')
<script>
let html5QrCode = null;
let sudahScan = false;

function mulaiScan() {
    sudahScan = false;

    document.getElementById('btnMulai').style.display = 'none';
    document.getElementById('btnStop').style.display = 'inline-block';
    document.getElementById('hasilScan').style.display = 'none';
    document.getElementById('errorScan').style.display = 'none';
    document.getElementById('statusScan').style.display = 'block';
    document.getElementById('statusScan').innerText = '📷 Kamera aktif... arahkan ke barcode';

    html5QrCode = new Html5Qrcode("reader");

    const config = {
        fps: 10,
        qrbox: { width: 250, height: 120 },
        formatsToSupport: [
            Html5QrcodeSupportedFormats.CODE_128,
            Html5QrcodeSupportedFormats.EAN_13,
            Html5QrcodeSupportedFormats.EAN_8,
            Html5QrcodeSupportedFormats.CODE_39,
            Html5QrcodeSupportedFormats.ITF,
        ]
    };

    html5QrCode.start(
        { facingMode: "environment" },
        config,
        function onScanSuccess(decodedText, decodedResult) {
            if (sudahScan) return;
            sudahScan = true;

            console.log('Kode terdeteksi:', decodedText);

            bunyikanBeep();
            stopScan();
            cariBarang(decodedText);
        },
        function onScanFailure(error) {
            // diabaikan — normal terjadi tiap frame
        }
    ).catch(function (err) {
        alert("Kamera tidak bisa diakses: " + err);
        document.getElementById('btnMulai').style.display = 'inline-block';
        document.getElementById('btnStop').style.display = 'none';
    });
}

function stopScan() {
    if (html5QrCode) {
        html5QrCode.stop().then(() => {
            html5QrCode.clear();
        }).catch(() => {});
    }
    document.getElementById('btnMulai').style.display = 'inline-block';
    document.getElementById('btnStop').style.display = 'none';
    document.getElementById('statusScan').style.display = 'none';
}

function cariBarang(kode) {
    fetch('/api/barang/' + kode)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                document.getElementById('kode-tidak-ditemukan').innerText = kode;
                document.getElementById('errorScan').style.display = 'block';
                return;
            }
            document.getElementById('res-id').innerText    = data.id_barang;
            document.getElementById('res-nama').innerText  = data.nama;
            document.getElementById('res-harga').innerText = 'Rp ' + Number(data.harga).toLocaleString('id-ID');
            document.getElementById('hasilScan').style.display = 'block';
        })
        .catch(() => {
            document.getElementById('kode-tidak-ditemukan').innerText = kode;
            document.getElementById('errorScan').style.display = 'block';
        });
}

function bunyikanBeep() {
    var beep = document.getElementById('beep');
    if (beep) {
        beep.currentTime = 0;
        beep.play().catch(() => {
            var ctx = new (window.AudioContext || window.webkitAudioContext)();
            var osc = ctx.createOscillator();
            var gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.value = 1000;
            gain.gain.value = 0.3;
            osc.start();
            osc.stop(ctx.currentTime + 0.15);
        });
    }
}

function scanLagi() {
    document.getElementById('hasilScan').style.display = 'none';
    document.getElementById('errorScan').style.display = 'none';
    mulaiScan();
}
</script>
@endsection