@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')
@section('icon', 'mdi mdi-pencil')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item"><a href="/kategori">Kategori</a></li>
<li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')

<div class="row">
<div class="col-md-12 grid-margin stretch-card">

<div class="card">
<div class="card-body">

<h4 class="card-title">Form Edit Kategori</h4>
<p class="card-description">Ubah data kategori</p>

<form id="formKategori" class="forms-sample" action="{{ route('kategori.update', $kategori->idkategori) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Nama Kategori</label>
        <input type="text"
               name="nama_kategori"
               value="{{ $kategori->nama_kategori }}"
               class="form-control" required>
    </div>

    <button type="button" id="btnSubmit" class="btn btn-warning me-2">Update</button>
    <a href="{{ route('kategori.index') }}" class="btn btn-light">Kembali</a>

</form>

</div>
</div>

</div>
</div>

@endsection

@section('js-page')

<script>
document.getElementById("btnSubmit").addEventListener("click", function(){
    let form = document.getElementById("formKategori");

    if(!form.checkValidity()){
        form.reportValidity();
        return;
    }

    this.innerHTML = `
        <span class="spinner-border spinner-border-sm"></span>
        Processing...
    `;
    this.disabled = true;
    form.submit();
});
</script>

@endsection