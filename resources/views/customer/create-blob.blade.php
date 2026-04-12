@extends('layouts.app')

@section('title', 'Tambah Customer (Blob)')
@section('page-title', 'Tambah Customer 1')
@section('icon', 'mdi mdi-camera')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">

                <h5 class="fw-bold mb-4">
                    <i class="mdi mdi-camera text-primary me-2"></i>
                    Tambah Customer - Simpan Foto sebagai Blob
                </h5>

                {{-- INPUT DATA --}}
                <div class="mb-3">
                    <label class="form-label fw-medium">Nama</label>
                    <input type="text" id="nama" class="form-control rounded-3" placeholder="Masukkan nama customer">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium">Alamat</label>
                    <input type="text" id="alamat" class="form-control rounded-3" placeholder="Masukkan alamat">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Provinsi</label>
                        <input type="text" id="provinsi" class="form-control rounded-3" placeholder="Provinsi">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Kota</label>
                        <input type="text" id="kota" class="form-control rounded-3" placeholder="Kota">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Kecamatan</label>
                        <input type="text" id="kecamatan" class="form-control rounded-3" placeholder="Kecamatan">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Kodepos - Kelurahan</label>
                        <input type="text" id="kodepos" class="form-control rounded-3" placeholder="Kodepos - Kelurahan">
                    </div>
                </div>

                {{-- FOTO --}}
                <div class="mb-3">
                    <label class="form-label fw-medium">Foto</label>
                    <div class="d-flex align-items-center gap-3">
                        {{-- Preview foto --}}
                        <div id="boxFoto" style="
                            width: 120px; height: 120px;
                            border: 2px dashed #ccc;
                            border-radius: 12px;
                            display: flex; align-items: center;
                            justify-content: center;
                            overflow: hidden;
                            background: #f8f9fa;
                        ">
                            <img id="previewFoto" src="" alt="Foto"
                                style="width:100%; height:100%; object-fit:cover; display:none;">
                            <span id="labelFoto" class="text-muted small">Foto</span>
                        </div>

                        <div>
                            <button onclick="bukaModal()" class="btn btn-outline-primary mb-2 d-block">
                                <i class="mdi mdi-camera"></i> Ambil Foto
                            </button>
                            <button onclick="simpanData()" class="btn btn-success d-block">
                                <i class="mdi mdi-content-save"></i> Simpan Data
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

{{-- MODAL KAMERA --}}
<div id="modalKamera" style="
    display: none;
    position: fixed; top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
    z-index: 9999;
    align-items: center;
    justify-content: center;
">
    <div style="
        background: white;
        border-radius: 16px;
        padding: 24px;
        width: 600px;
        max-width: 95%;
    ">
        <h5 class="fw-bold mb-3">Modal Ambil Foto</h5>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label small text-muted">Video</label>
                <video id="video" autoplay style="
                    width: 100%; border-radius: 8px;
                    border: 2px solid #e0e0e0;
                    background: #000;
                "></video>
            </div>
            <div class="col-md-6">
                <label class="form-label small text-muted">Snapshot</label>
                <canvas id="canvas" style="
                    width: 100%; border-radius: 8px;
                    border: 2px solid #e0e0e0;
                "></canvas>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <button onclick="ambilFoto()" class="btn btn-primary">
                <i class="mdi mdi-camera"></i> Ambil Foto
            </button>
            <button onclick="simpanFoto()" class="btn btn-success">
                <i class="mdi mdi-check"></i> Simpan Foto
            </button>
        </div>

        <div class="text-end mt-2">
            <button onclick="tutupModal()" class="btn btn-link text-danger">Batal</button>
        </div>

    </div>
</div>

<script>
let streamAktif = null;
let fotoBase64 = null;

// Buka modal & aktifkan kamera
function bukaModal() {
    document.getElementById('modalKamera').style.display = 'flex';
    navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
        streamAktif = stream;
        document.getElementById('video').srcObject = stream;
    });
}

function tutupModal() {
    document.getElementById('modalKamera').style.display = 'none';
    if (streamAktif) {
        streamAktif.getTracks().forEach(t => t.stop());
    }
}


// Ambil snapshot
function ambilFoto() {
    let video = document.getElementById('video');
    let canvas = document.getElementById('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
}

// Simpan foto ke preview
function simpanFoto() {
    let canvas = document.getElementById('canvas');
    fotoBase64 = canvas.toDataURL('image/png');

    if (fotoBase64.length < 100) {
        alert('Ambil foto dulu!');
        return;
    }

    // tampilkan di preview
    let preview = document.getElementById('previewFoto');
    preview.src = fotoBase64;
    preview.style.display = 'block';
    document.getElementById('labelFoto').style.display = 'none';

    tutupModal();
}

// Simpan data customer
function simpanData() {
    let nama = document.getElementById('nama').value;
    let alamat = document.getElementById('alamat').value;
    let provinsi = document.getElementById('provinsi').value;
    let kota = document.getElementById('kota').value;
    let kecamatan = document.getElementById('kecamatan').value;
    let kodepos = document.getElementById('kodepos').value;

    if (!nama) { alert('Nama wajib diisi!'); return; }
    if (!fotoBase64) { alert('Ambil foto dulu!'); return; }

    fetch('/customer/store-blob', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            nama: nama,
            alamat: alamat,
            provinsi: provinsi,
            kota: kota,
            kecamatan: kecamatan,
            kodepos: kodepos,
            foto: fotoBase64
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            alert('Gagal: ' + data.error);
            return;
        }
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data customer berhasil disimpan'
        }).then(() => {
            window.location.href = '{{ route("customer.index") }}';
        });
    });
}
</script>

<script src="https://unpkg.com/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

@endsection