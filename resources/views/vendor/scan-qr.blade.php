@extends('layouts.app')

@section('title', 'Scan QR Customer')
@section('page-title', 'Scan QR Code Customer')
@section('icon', 'mdi mdi-qrcode-scan')

@section('no-sidebar')
@endsection

@section('no-sidebar-class', 'full-page-wrapper')

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">

            {{-- Tombol Kembali ke Index Vendor --}}
            <div class="mb-3">
                <a href="{{ route('vendor.index') }}" class="btn btn-outline-secondary rounded-3">
                    <i class="mdi mdi-arrow-left me-1"></i> Kembali ke Dashboard Vendor
                </a>
            </div>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body text-center p-4">

                    <h5 class="fw-semibold mb-3">Scanner QR Code Customer</h5>

                    <!-- Area Kamera — pakai html5-qrcode sama seperti scan-barcode -->
                    <div id="reader" style="
                        width: 100%;
                        border-radius: 12px;
                        overflow: hidden;
                    "></div>

                    <p class="text-muted mt-2 small">Arahkan kamera ke QR Code customer</p>

                    <!-- Status -->
                    <div id="statusScan" class="alert alert-info mt-2 py-2 small" style="display:none;"></div>

                    <button id="btnMulai" onclick="mulaiScan()" class="btn btn-primary rounded-3 px-4 mt-2">
                        <i class="mdi mdi-qrcode-scan me-1"></i> Mulai Scan
                    </button>
                    <button id="btnStop" onclick="stopScan()" class="btn btn-danger rounded-3 px-4 mt-2" style="display:none;">
                        <i class="mdi mdi-stop me-1"></i> Stop
                    </button>

                </div>
            </div>

            <!-- Hasil Scan -->
            <div id="hasilScan" class="card shadow-sm border-0 rounded-4 mt-4" style="display:none;">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-semibold mb-0">Detail Pesanan</h5>
                        <span id="badge-status"></span>
                    </div>

                    <p class="text-muted small mb-2">Order ID: <strong id="res-order-id"></strong></p>
                    <p class="text-muted small mb-3">Nama: <strong id="res-nama"></strong></p>

                    <table class="table table-bordered rounded-3">
                        <thead class="table-light">
                            <tr>
                                <th>Menu</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="res-menu"></tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td colspan="2">Total</td>
                                <td id="res-total"></td>
                            </tr>
                        </tfoot>
                    </table>

                    <button onclick="scanLagi()" class="btn btn-outline-primary w-100 rounded-3 mt-2">
                        <i class="mdi mdi-refresh me-1"></i> Scan Lagi
                    </button>

                </div>
            </div>

            <!-- Error -->
            <div id="errorScan" class="alert alert-danger mt-3 rounded-3" style="display:none;">
                ❌ Pesanan tidak ditemukan! Kode: <strong id="kode-tidak-ditemukan"></strong>
            </div>

        </div>
    </div>
</div>

@endsection

<!-- Library html5-qrcode — sama seperti scan-barcode -->
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
    document.getElementById('statusScan').innerText = '📷 Kamera aktif... arahkan ke QR Code';

    html5QrCode = new Html5Qrcode("reader");

    const config = {
        fps: 10,
        // qrbox persegi untuk QR Code (beda dengan barcode yang lebar)
        qrbox: { width: 220, height: 220 },
        formatsToSupport: [
            Html5QrcodeSupportedFormats.QR_CODE,
            Html5QrcodeSupportedFormats.CODE_128,
        ]
    };

    html5QrCode.start(
        { facingMode: "environment" },
        config,
        function onScanSuccess(decodedText, decodedResult) {
            if (sudahScan) return;
            sudahScan = true;

            console.log('QR terdeteksi:', decodedText);

            bunyikanBeep();
            stopScan();
            cariPesanan(decodedText);
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

function cariPesanan(orderId) {
    fetch('/api/pesanan/' + orderId)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                document.getElementById('kode-tidak-ditemukan').innerText = orderId;
                document.getElementById('errorScan').style.display = 'block';
                return;
            }

            document.getElementById('res-order-id').innerText = '#' + data.idpesanan;
            document.getElementById('res-nama').innerText = data.nama;
            document.getElementById('res-total').innerText = 'Rp ' + Number(data.total).toLocaleString('id-ID');

            let badge = data.status_bayar == 1
                ? '<span class="badge bg-success fs-6">Lunas</span>'
                : '<span class="badge bg-warning text-dark fs-6">⏳ Belum Bayar</span>';
            document.getElementById('badge-status').innerHTML = badge;

            let html = '';
            data.detail_pesanan.forEach(item => {
                html += `
                <tr>
                    <td>${item.menu.nama_menu}</td>
                    <td>${item.jumlah}</td>
                    <td>Rp ${Number(item.subtotal).toLocaleString('id-ID')}</td>
                </tr>`;
            });
            document.getElementById('res-menu').innerHTML = html;
            document.getElementById('hasilScan').style.display = 'block';
        })
        .catch(() => {
            document.getElementById('kode-tidak-ditemukan').innerText = orderId;
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