@extends('layouts.guest')

@section('title', 'QR Code Saya')
@section('page-title', 'QR Code Pesanan')
@section('icon', 'mdi mdi-qrcode')

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow-sm border-0 rounded-4 text-center">
                <div class="card-body p-4">

                    <h5 class="fw-semibold mb-1">QR Code Pesanan Kamu</h5>
                    <p class="text-muted small mb-4">Tunjukkan ke vendor untuk konfirmasi pesanan</p>

                    <div id="qrcode-area" style="
                        display: inline-block;
                        padding: 16px;
                        border: 1px solid #eee;
                        border-radius: 12px;
                    "></div>

                    <p id="order-label" class="text-muted mt-3 small"></p>

                    <div id="detail-pesanan" class="mt-3 text-start" style="display:none;">
                        <hr>
                        <h6 class="fw-semibold">Detail Pesanan</h6>
                        <div id="list-menu"></div>
                        <div class="d-flex justify-content-between mt-2 fw-bold">
                            <span>Total</span>
                            <span id="total-pesanan"></span>
                        </div>
                    </div>

                    <div id="tidak-ada" style="display:none;" class="mt-3">
                        <p class="text-muted">Belum ada pesanan. <a href="/">Pesan sekarang</a></p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<div class="text-center mt-3 font-weight-light">
    <a href="{{ route('welcome') }}" class="text-primary">
        ← Kembali
    </a>
</div>

@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    let orderId = localStorage.getItem('last_order_id');

    if (!orderId) {
        document.getElementById('tidak-ada').style.display = 'block';
        document.getElementById('qrcode-area').style.display = 'none';
        return;
    }

    document.getElementById('order-label').innerText = 'Order ID: #' + orderId;

    // Generate QR Code
    new QRCode(document.getElementById("qrcode-area"), {
        text: String(orderId),
        width: 200,
        height: 200
    });

    // Load detail pesanan
    fetch('/api/pesanan/' + orderId)
        .then(res => res.json())
        .then(data => {
            if (data.error) return;

            let html = '';
            data.detail_pesanan.forEach(item => {
                html += `
                <div class="d-flex justify-content-between py-1">
                    <span>${item.menu.nama_menu} x${item.jumlah}</span>
                    <span>Rp ${item.subtotal.toLocaleString()}</span>
                </div>`;
            });

            document.getElementById('list-menu').innerHTML = html;
            document.getElementById('total-pesanan').innerText = 'Rp ' + data.total.toLocaleString();
            document.getElementById('detail-pesanan').style.display = 'block';
        });
});
</script>