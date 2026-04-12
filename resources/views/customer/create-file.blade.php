@extends('layouts.app')

@section('title', 'Tambah Customer (File)')
@section('page-title', 'Tambah Customer 2')
@section('icon', 'mdi mdi-file-image')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">

                <h5 class="fw-bold mb-4">
                    <i class="mdi mdi-file-image text-success me-2"></i>
                    Tambah Customer - Simpan Foto sebagai File
                </h5>

                <form action="{{ route('customer.storeFile') }}" method="POST" enctype="multipart/form-data" id="formCustomer">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nama</label>
                        <input type="text" name="nama" class="form-control rounded-3" placeholder="Masukkan nama customer" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Alamat</label>
                        <input type="text" name="alamat" class="form-control rounded-3" placeholder="Masukkan alamat">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Provinsi</label>
                            <input type="text" name="provinsi" class="form-control rounded-3" placeholder="Provinsi">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Kota</label>
                            <input type="text" name="kota" class="form-control rounded-3" placeholder="Kota">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control rounded-3" placeholder="Kecamatan">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Kodepos - Kelurahan</label>
                            <input type="text" name="kodepos" class="form-control rounded-3" placeholder="Kodepos - Kelurahan">
                        </div>
                    </div>

                    {{-- hidden input untuk foto dari kamera --}}
                    <input type="hidden" name="foto" id="fotoInput">

                    {{-- FOTO --}}
                    <div class="mb-4">
                        <label class="form-label fw-medium">Foto</label>
                        <div class="d-flex align-items-center gap-3">
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
                                <button type="button" onclick="bukaModal()" class="btn btn-outline-success mb-2 d-block">
                                    <i class="mdi mdi-camera"></i> Ambil Foto
                                </button>
                                <button type="submit" class="btn btn-success d-block">
                                    <i class="mdi mdi-content-save"></i> Simpan Data
                                </button>
                            </div>
                        </div>
                    </div>

                </form>

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
    if (streamAktif) streamAktif.getTracks().forEach(t => t.stop());
}

function aktifkanKamera(deviceId) {
    if (streamAktif) streamAktif.getTracks().forEach(t => t.stop());
    navigator.mediaDevices.getUserMedia({
        video: deviceId ? { deviceId: { exact: deviceId } } : true
    }).then(stream => {
        streamAktif = stream;
        document.getElementById('video').srcObject = stream;
    });
}

function ambilFoto() {
    let video = document.getElementById('video');
    let canvas = document.getElementById('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
}

function simpanFoto() {
    let canvas = document.getElementById('canvas');
    let fotoBase64 = canvas.toDataURL('image/png');

    if (fotoBase64.length < 100) {
        alert('Ambil foto dulu!');
        return;
    }

    // masukkan ke hidden input agar ikut tersubmit form
    document.getElementById('fotoInput').value = fotoBase64;

    // tampilkan preview
    let preview = document.getElementById('previewFoto');
    preview.src = fotoBase64;
    preview.style.display = 'block';
    document.getElementById('labelFoto').style.display = 'none';

    tutupModal();
}
</script>

@endsection