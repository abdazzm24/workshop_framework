@extends('layouts.app')

@section('title', 'Tambah Barang')
@section('page-title', 'Tambah Barang')
@section('icon', 'mdi mdi-package')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item"><a href="/barang">Barang</a></li>
<li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection


@section('content')

<div class="row">
<div class="col-md-12 grid-margin stretch-card">

<div class="card">
<div class="card-body">

<h4 class="card-title">Form Tambah Barang</h4>
<p class="card-description">Masukkan data barang</p>

<form id="formBarang" class="forms-sample" action="{{ route('barang.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Harga</label>
        <input type="number" name="harga" class="form-control" required>
    </div>

    <button type="button" id="btnSubmit" class="btn btn-gradient-primary me-2">Simpan</button>
    <a href="{{ route('barang.index') }}" class="btn btn-light">Kembali</a>

</form>

</div>
</div>
</div>
</div>

@endsection

@section('js-page')

<script> // yang baru ditambahkan

document.getElementById("btnSubmit").addEventListener("click", function(){

    let form = document.getElementById("formBarang");

    // cek apakah input required sudah terisi
    if(!form.checkValidity()){

        // menampilkan pesan error bawaan HTML5
        form.reportValidity();
        return;

    }

    // ubah tombol menjadi spinner
    this.innerHTML = `
        <span class="spinner-border spinner-border-sm"></span>
        Processing...
    `;

    // disable tombol agar tidak double submit
    this.disabled = true;

    // submit form
    form.submit();

});

</script>

@endsection