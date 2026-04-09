@extends('layouts.guest')

@section('title', 'Customer')
@section('page-title', 'Halaman Customer')
@section('icon', 'mdi mdi-account')

@section('content')

<div class="container py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">🍽️ Kantin Online</h3>
            <small class="text-muted">Pesan makanan favoritmu dengan mudah</small>
        </div>

        {{-- ✅ TETAP ADA LOGIN --}}
        <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-3 px-4">
            Login
        </a>
    </div>

    <div class="row g-4">

        <!-- FORM -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">

                    <h5 class="mb-4 fw-semibold">🧾 Pilih Menu</h5>

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
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">

                    <h5 class="mb-4 fw-semibold">🛒 Keranjang</h5>

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
                            <button onclick="bayar()" class="btn btn-success px-4 py-2 rounded-3">
                                💳 Bayar
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

@endsection

<script>

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

}

function hapusItem(index, subtotal) {

    total -= subtotal;

    document.getElementById('row-' + index).remove();
    document.getElementById('total').innerText = "Rp " + total.toLocaleString();

    keranjang.splice(index, 1);
}

function bayar() {

    if (keranjang.length === 0) {
        alert('Keranjang kosong!');
        return;
    }

    fetch('/checkout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            items: keranjang,
            total: total
        })
    })
    .then(res => res.json())
    .then(data => {

        alert('Transaksi berhasil!');
        location.reload();

    });
}

</script>