@extends('layouts.guest')

@section('title', 'Customer')
@section('page-title', 'Halaman Customer')
@section('icon', 'mdi mdi-account')

@section('content')

<div class="container py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Pesan Online</h3>
            <small class="text-muted">Pesan makanan favoritmu lewat sini</small>
        </div>

        {{-- ✅ TETAP ADA LOGIN --}}
        <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-3 px-4">
            Login
        </a>
    </div>

    <div class="row g-4">

        <!-- 🔥 FORM CUSTOMER -->
        <div class="card mb-4">
            <div class="card-body">

                <h5>Isi Data Customer</h5>

                <input type="text" id="nama_customer" class="form-control mb-2" placeholder="Masukkan nama">

                <!-- 🔥 TAMBAHAN: kamera lebih kecil -->
                <video id="video" width="100%" height="180" autoplay class="mb-2 rounded"></video>
                <!-- 🔥 TAMBAHAN: preview foto -->
                <img id="preview" class="img-fluid mb-2 rounded" style="display:none; max-height:150px; object-fit:cover;">

                <canvas id="canvas" style="display:none;"></canvas>


                <button onclick="ambilFoto()" class="btn btn-secondary w-100 mb-2">
                    Ambil Foto
                </button>

                <button onclick="simpanCustomer()" class="btn btn-success w-100">
                    Mulai Pesan
                </button>

            </div>
        </div>

        <!-- FORM -->
        <div id="formPesanan" class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">

                    <h5 class="mb-4 fw-semibold">Pilih Pesanan</h5>

                    <!-- Vendor -->
                    <div class="mb-3">
                        <label class="form-label fw-medium">Vendor</label>
                        <select id="vendor" class="form-select rounded-3" onchange="loadMenu()">
                            <option value="">-- Pilih Vendor --</option>

                            {{-- ✅ DIUBAH: idvendor --}}
                            @foreach($vendors as $v)
                                <option value="{{ $v->idvendor }}">
                                    {{ $v->nama_vendor }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <!-- Menu -->
                    <div class="mb-3">
                        <label class="form-label fw-medium">Menu</label>
                        <select id="menu" class="form-select rounded-3" onchange="setHarga()">
                            <option value="">-- Pilih Menu --</option>
                        </select>
                    </div>

                    <!-- Harga -->
                    <div class="mb-3">
                        <label class="form-label fw-medium">Harga</label>
                        <input type="text" id="harga" class="form-control bg-light rounded-3" readonly>
                    </div>

                    <!-- Jumlah -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Jumlah</label>
                        <input type="number" id="jumlah" class="form-control rounded-3">
                    </div>

                    <button onclick="tambahBarang()" class="btn btn-primary w-100 rounded-3 py-2">
                        + Tambah ke Keranjang
                    </button>

                </div>
            </div>
        </div>

        <!-- KERANJANG -->
        <div id="keranjangSection" class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">

                    <h5 class="mb-4 fw-semibold">Keranjang</h5>

                    <div class="mb-3">
                        <strong>Customer:</strong>
                        <span id="namaCustomerView" class="text-primary">-</span><br>
                        <img id="fotoCustomerView" style="max-height:80px; display:none;" class="rounded">
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Menu</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th> {{-- ✅ BARU --}}
                                </tr>
                            </thead>
                            <tbody id="tabelBarang"></tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 p-3 bg-light rounded-3">
                        <h5 class="mb-0">
                            Total: <span id="total" class="text-primary fw-bold">Rp 0</span>
                        </h5>

                        <div>
                            <button id="btnBayar" onclick="bayar()" class="btn btn-success px-4 py-2 rounded-3">
                                Bayar
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

@endsection

<script src="https://unpkg.com/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

@if(config('midtrans.is_production'))
    <script src="https://app.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
    </script>
@else
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
    </script>
@endif

<script>

let customerId = null;

// 🔥 AKTIFKAN KAMERA
navigator.mediaDevices.getUserMedia({ video: true })
.then(stream => {
    document.getElementById('video').srcObject = stream;
});

// 🔥 AMBIL FOTO
function ambilFoto() {
    let canvas = document.getElementById('canvas');
    let video = document.getElementById('video');

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    canvas.getContext('2d').drawImage(video, 0, 0);

    let foto = canvas.toDataURL('image/png');
    let preview = document.getElementById('preview');

    preview.src = foto;
    preview.style.display = 'block';

    alert('Foto berhasil diambil');
}

// 🔥 SIMPAN CUSTOMER
function simpanCustomer() {

    let nama = document.getElementById('nama_customer').value;
    let canvas = document.getElementById('canvas');

    if (!nama) {
        alert('Nama wajib diisi!');
        return;
    }

    let foto = canvas.toDataURL('image/png');

    if (foto.length < 100) {
        alert('Ambil foto dulu!');
        return;
    }

    fetch('/customer/store-blob', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            nama: nama,
            foto: foto
        })
    })
    .then(res => res.json())
    .then(data => {

        console.log(data);

        if (!data.id) {
            alert('Gagal ambil ID customer!');
            return;
        }
        
        customerId = data.id;

        alert('Customer berhasil dibuat!');

        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Sekarang kamu bisa mulai pesan makanan'
        });

        document.getElementById('namaCustomerView').innerText = nama;

        let preview = document.getElementById('preview');
        let fotoView = document.getElementById('fotoCustomerView');

        fotoView.src = preview.src;
        fotoView.style.display = 'block';
        
        // Aktifkan form pemesanan
        document.getElementById('formPesanan').style.pointerEvents = 'auto';
        document.getElementById('keranjangSection').style.pointerEvents = 'auto';
        document.getElementById('formPesanan').style.opacity = '1';
        document.getElementById('keranjangSection').style.opacity = '1';

    });
}

let total = 0;
let menus = [];
let keranjang = [];

function loadMenu() {

    let vendorId = document.getElementById('vendor').value;

    fetch('/get-menu/' + vendorId)
        .then(res => res.json())
        .then(data => {

            menus = data;

            let menuSelect = document.getElementById('menu');
            menuSelect.innerHTML = '<option value="">-- Pilih Menu --</option>';

            data.forEach(m => {

                // ✅ DIUBAH: idmenu
                menuSelect.innerHTML += `
                    <option value="${m.idmenu}">
                        ${m.nama_menu} - Rp ${m.harga}
                    </option>
                `;
            });

        });
}

function setHarga() {

    let menuId = document.getElementById('menu').value;
    // ✅ DIUBAH: idmenu
    let selected = menus.find(m => m.idmenu == menuId);

    if (selected) {
        document.getElementById('harga').value = "Rp " + selected.harga.toLocaleString();
        document.getElementById('harga').dataset.value = selected.harga;
    }
}

function tambahBarang() {

    let menuId = document.getElementById('menu').value;
    let menuText = document.getElementById('menu').selectedOptions[0].text;
    let harga = parseInt(document.getElementById('harga').dataset.value);
    let jumlah = parseInt(document.getElementById('jumlah').value);

    if (!menuId || !harga || !jumlah) {
        alert('Lengkapi data!');
        return;
    }

    let subtotal = harga * jumlah;
    total += subtotal;

    let index = keranjang.length;

    keranjang.push({
        menu_id: menuId,
        qty: jumlah,
        harga: harga,
        subtotal: subtotal
    });

    // ✅ DITAMBAH: tombol hapus
    let row = `
    <tr id="row-${index}">
        <td>${menuText}</td>
        <td>Rp ${harga.toLocaleString()}</td>
        <td>${jumlah}</td>
        <td>Rp ${subtotal.toLocaleString()}</td>
        <td>
            <button onclick="hapusItem(${index}, ${subtotal})" class="btn btn-sm btn-danger">
                Hapus
            </button>
        </td>
    </tr>
    `;

    document.getElementById('tabelBarang').innerHTML += row;
    document.getElementById('total').innerText = "Rp " + total.toLocaleString();

    if (jumlah <= 0) {
        alert('Jumlah harus lebih dari 0!');
        return;
    }

}

function hapusItem(index, subtotal) {

    total -= subtotal;

    document.getElementById('row-' + index).remove();
    document.getElementById('total').innerText = "Rp " + total.toLocaleString();

    let items = keranjang.filter(item => item != null);
}

function bayar() {

    console.log("Customer ID:", customerId);
    
    if (!customerId) {
        alert('Isi data customer dulu!');
        return;
    }

    if (keranjang.length === 0) {
        alert('Keranjang kosong!');
        return;
    }

    let btn = document.getElementById("btnBayar");
    btn.disabled = true;
    btn.innerHTML = "Memproses pembayaran...";

    fetch('/checkout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            customer_id: customerId,
            items: keranjang,
            total: total
        })
    })
    .then(res => res.json())
    .then(data => {

        if(data.error){
            alert(data.error);
            btn.disabled = false;
            btn.innerHTML = "Bayar";
            return;
        }

        snap.pay(data.snap_token, {
            onSuccess: function(){
                location.reload();
            },
            onPending: function(){

                // ✅ Saat QRIS muncul, tunggu 5 detik lalu simulasi sukses
                setTimeout(function(){
                    snap.hide(); // tutup popup QRIS

                    // buat popup manual tanpa library
                    document.body.innerHTML += `
                    <div id="popupSukses" style="
                        position: fixed; top: 0; left: 0;
                        width: 100%; height: 100%;
                        background: rgba(0,0,0,0.6);
                        display: flex; align-items: center;
                        justify-content: center; z-index: 9999;
                    ">
                        <div style="
                            background: white; border-radius: 16px;
                            padding: 48px; text-align: center;
                            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
                        ">
                            <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="40" cy="40" r="38" stroke="#28a745" stroke-width="2"/>
                                <polyline points="22,42 34,54 58,28" stroke="#28a745" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <h2 style="color: #28a745; margin: 16px 0 8px;">Pembayaran Berhasil!</h2>
                            <p style="color: #666;">Pesanan kamu sedang diproses</p>
                        </div>
                    </div>
                    `;

                    // update status di database
                    fetch('/bayar-sukses/' + data.order_id, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    setTimeout(() => { location.reload(); }, 3000);

                }, 5000); // 5 detik
            },
            onError: function(){
                alert("Pembayaran gagal");
                btn.disabled = false;
                btn.innerHTML = "Bayar";
            }
        });
    });
}

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('formPesanan').style.pointerEvents = 'none';
    document.getElementById('keranjangSection').style.pointerEvents = 'none';

    document.getElementById('formPesanan').style.opacity = '0.5';
    document.getElementById('keranjangSection').style.opacity = '0.5';
});

</script>

