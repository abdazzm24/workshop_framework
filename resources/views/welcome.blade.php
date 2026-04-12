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

        <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-3 px-4">
            Login
        </a>
    </div>

    <div class="row g-4">

        <!-- 🔥 PILIH CUSTOMER -->
        <div class="card mb-4">
            <div class="card-body">

                <h5 class="fw-semibold mb-3">Pilih Customer</h5>

                <select id="customerSelect" class="form-select rounded-3 mb-3" onchange="pilihCustomer()">
                    <option value="">-- Pilih Customer --</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}"
                            data-nama="{{ $c->nama }}"
                            data-foto="{{ $c->foto_blob ?? ($c->foto_path ? asset('storage/' . $c->foto_path) : '') }}">
                            {{ $c->nama }}
                        </option>
                    @endforeach
                </select>

                <!-- Preview customer yang dipilih -->
                <div id="previewCustomer" style="display:none;" class="d-flex align-items-center gap-3 p-3 bg-light rounded-3">
                    <img id="fotoCustomerPreview"
                        style="width:70px; height:70px; object-fit:cover; border-radius:10px; border:2px solid #dee2e6;">
                    <div>
                        <div class="fw-bold" id="namaCustomerPreview">-</div>
                        <small class="text-muted">Customer terpilih</small>
                    </div>
                </div>

                <button onclick="konfirmasiCustomer()" class="btn btn-success w-100 mt-3">
                    Mulai Pesan
                </button>

            </div>
        </div>

        <!-- FORM PESANAN -->
        <div id="formPesanan" class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">

                    <h5 class="mb-4 fw-semibold">Pilih Pesanan</h5>

                    <!-- Vendor -->
                    <div class="mb-3">
                        <label class="form-label fw-medium">Vendor</label>
                        <select id="vendor" class="form-select rounded-3" onchange="loadMenu()">
                            <option value="">-- Pilih Vendor --</option>
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
                        <input type="number" id="jumlah" class="form-control rounded-3" min="1">
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

                    <!-- Info customer terpilih -->
                    <div class="mb-3 d-flex align-items-center gap-3">
                        <img id="fotoCustomerView"
                            style="width:60px; height:60px; object-fit:cover; border-radius:10px; display:none; border:2px solid #dee2e6;">
                        <div>
                            <strong>Customer:</strong>
                            <span id="namaCustomerView" class="text-primary ms-1">-</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Menu</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tabelBarang"></tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 p-3 bg-light rounded-3">
                        <h5 class="mb-0">
                            Total: <span id="total" class="text-primary fw-bold">Rp 0</span>
                        </h5>
                        <button id="btnBayar" onclick="bayar()" class="btn btn-success px-4 py-2 rounded-3">
                            Bayar
                        </button>
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
let total = 0;
let menus = [];
let keranjang = [];

// 🔥 Saat dropdown customer berubah → tampilkan preview
function pilihCustomer() {
    let select = document.getElementById('customerSelect');
    let option = select.selectedOptions[0];

    let nama = option.dataset.nama;
    let foto = option.dataset.foto;

    if (!select.value) {
        document.getElementById('previewCustomer').style.display = 'none';
        return;
    }

    document.getElementById('namaCustomerPreview').innerText = nama;

    let fotoEl = document.getElementById('fotoCustomerPreview');
    if (foto) {
        fotoEl.src = foto;
        fotoEl.style.display = 'block';
    } else {
        fotoEl.style.display = 'none';
    }

    document.getElementById('previewCustomer').style.display = 'flex';
}

// 🔥 Konfirmasi customer → aktifkan form pesanan
function konfirmasiCustomer() {
    let select = document.getElementById('customerSelect');
    let option = select.selectedOptions[0];

    if (!select.value) {
        Swal.fire({ icon: 'warning', title: 'Pilih customer dulu!' });
        return;
    }

    customerId = select.value;
    let nama = option.dataset.nama;
    let foto = option.dataset.foto;

    // update keranjang
    document.getElementById('namaCustomerView').innerText = nama;

    let fotoView = document.getElementById('fotoCustomerView');
    if (foto) {
        fotoView.src = foto;
        fotoView.style.display = 'block';
    }

    // aktifkan form
    document.getElementById('formPesanan').style.pointerEvents = 'auto';
    document.getElementById('keranjangSection').style.pointerEvents = 'auto';
    document.getElementById('formPesanan').style.opacity = '1';
    document.getElementById('keranjangSection').style.opacity = '1';

    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Halo ' + nama + '! Silakan pilih pesananmu.',
        timer: 1500,
        showConfirmButton: false
    });
}

function loadMenu() {
    let vendorId = document.getElementById('vendor').value;
    fetch('/get-menu/' + vendorId)
        .then(res => res.json())
        .then(data => {
            menus = data;
            let menuSelect = document.getElementById('menu');
            menuSelect.innerHTML = '<option value="">-- Pilih Menu --</option>';
            data.forEach(m => {
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

    if (!menuId || !harga || !jumlah || jumlah <= 0) {
        alert('Lengkapi data dengan benar!');
        return;
    }

    let subtotal = harga * jumlah;
    total += subtotal;

    let index = keranjang.length;
    keranjang.push({ menu_id: menuId, qty: jumlah, harga: harga, subtotal: subtotal });

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
    </tr>`;

    document.getElementById('tabelBarang').innerHTML += row;
    document.getElementById('total').innerText = "Rp " + total.toLocaleString();
}

function hapusItem(index, subtotal) {
    total -= subtotal;
    document.getElementById('row-' + index).remove();
    document.getElementById('total').innerText = "Rp " + total.toLocaleString();
}

function bayar() {
    if (!customerId) { alert('Pilih customer dulu!'); return; }
    if (keranjang.length === 0) { alert('Keranjang kosong!'); return; }

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
        if (data.error) {
            alert(data.error);
            btn.disabled = false;
            btn.innerHTML = "Bayar";
            return;
        }

        snap.pay(data.snap_token, {
            onSuccess: function() { location.reload(); },
            onPending: function() {
                setTimeout(function() {
                    snap.hide();

                    document.body.innerHTML += `
                    <div id="popupSukses" style="
                        position:fixed; top:0; left:0;
                        width:100%; height:100%;
                        background:rgba(0,0,0,0.6);
                        display:flex; align-items:center;
                        justify-content:center; z-index:9999;
                    ">
                        <div style="background:white; border-radius:16px; padding:48px; text-align:center;">
                            <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
                                <circle cx="40" cy="40" r="38" stroke="#28a745" stroke-width="2"/>
                                <polyline points="22,42 34,54 58,28" stroke="#28a745" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <h2 style="color:#28a745; margin:16px 0 8px;">Pembayaran Berhasil!</h2>
                            <p style="color:#666;">Pesanan kamu sedang diproses</p>
                        </div>
                    </div>`;

                    fetch('/bayar-sukses/' + data.order_id, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });

                    setTimeout(() => { location.reload(); }, 3000);
                }, 5000);
            },
            onError: function() {
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