@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori')
@section('icon', 'mdi mdi-tag-plus')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item"><a href="/kategori">Kategori</a></li>
<li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')

<div class="row">
<div class="col-md-12 grid-margin stretch-card">

<div class="card">
<div class="card-body">

<h4 class="card-title">Form Tambah Kategori</h4>
<p class="card-description">Tambah data kategori baru</p>

<form id="formKategori" class="forms-sample" action="{{ route('kategori.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label>Nama Kategori</label>
        <input type="text" name="nama_kategori" class="form-control" required>
    </div>

    <button type="button" id="btnSubmit" class="btn btn-gradient-primary me-2">Simpan</button>
    <a href="{{ route('kategori.index') }}" class="btn btn-light">Kembali</a>

</form>

</div>
</div>
</div>
</div>

@endsection

@section('js-page')

<script> // yang baru ditambahkan

document.getElementById("btnSubmit").addEventListener("click", function(){

    let form = document.getElementById("formKategori");

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