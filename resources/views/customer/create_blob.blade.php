@extends('layouts.app')

@section('title', 'Tambah Customer')

@section('content')

<h3>Tambah Customer (Kamera)</h3>

<input type="text" id="nama" placeholder="Nama Customer" class="form-control mb-3">

<video id="video" width="300" autoplay></video>
<br><br>

<button onclick="ambilFoto()" class="btn btn-primary">Ambil Foto</button>

<br><br>

<canvas id="canvas" width="300" height="200"></canvas>

<form method="POST" action="{{ route('customer.store.blob') }}">
    @csrf
    <input type="hidden" name="nama" id="inputNama">
    <input type="hidden" name="foto" id="foto">

    <button type="submit" class="btn btn-success mt-3">Simpan</button>
</form>

@endsection

<script>

let video = document.getElementById('video');

// 🔥 AKSES KAMERA
navigator.mediaDevices.getUserMedia({ video: true })
.then(stream => {
    video.srcObject = stream;
});

// 🔥 AMBIL FOTO
function ambilFoto() {
    let canvas = document.getElementById('canvas');
    let context = canvas.getContext('2d');

    context.drawImage(video, 0, 0, 300, 200);

    let data = canvas.toDataURL('image/png');

    document.getElementById('foto').value = data;
    document.getElementById('inputNama').value = document.getElementById('nama').value;
}

</script>