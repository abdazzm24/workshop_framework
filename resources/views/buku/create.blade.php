@extends('layouts.app')

@section('title', 'Tambah Buku')
@section('page-title', 'Tambah Buku')
@section('icon', 'mdi mdi-book-plus')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item"><a href="/buku">Buku</a></li>
<li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')

<div class="row">
<div class="col-md-12 grid-margin stretch-card">

<div class="card">
<div class="card-body">

<h4 class="card-title">Form Tambah Buku</h4>
<p class="card-description">Masukkan data buku</p>

<form id="formBuku" class="forms-sample" action="{{ route('buku.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label>Kategori</label>
        <select name="idkategori" class="form-control">
            @foreach($kategori as $item)
                <option value="{{ $item->idkategori }}">
                    {{ $item->nama_kategori }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Kode</label>
        <input type="text" name="kode" class="form-control" placeholder="Masukkan kode buku" required>
    </div>

    <div class="form-group">
        <label>Judul</label>
        <input type="text" name="judul" class="form-control" placeholder="Masukkan judul buku" required>
    </div>

    <div class="form-group">
        <label>Pengarang</label>
        <input type="text" name="pengarang" class="form-control" placeholder="Masukkan nama pengarang" required>
    </div>

    <button type="button" id="btnSubmit" class="btn btn-gradient-primary me-2">Simpan</button>
    <a href="{{ route('buku.index') }}" class="btn btn-light">Kembali</a>

</form>

</div>
</div>
</div>
</div>

@endsection

@section('js-page')

<script>
document.getElementById("btnSubmit").addEventListener("click", function(){
    let form = document.getElementById("formBuku");

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