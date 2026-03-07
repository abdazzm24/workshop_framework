@extends('layouts.app')

@section('title', 'Edit Buku')
@section('page-title', 'Edit Buku')
@section('icon', 'mdi mdi-pencil')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item"><a href="/buku">Buku</a></li>
<li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')

<div class="row">
<div class="col-md-12 grid-margin stretch-card">

<div class="card">
<div class="card-body">

<h4 class="card-title">Form Edit Buku</h4>
<p class="card-description">Ubah data buku</p>

<form id="formBuku" class="forms-sample" action="{{ route('buku.update', $buku->idbuku) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Kategori</label>
        <select name="idkategori" class="form-control">
            @foreach($kategori as $item)
                <option value="{{ $item->idkategori }}"
                    {{ $buku->idkategori == $item->idkategori ? 'selected' : '' }}>
                    {{ $item->nama_kategori }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Kode</label>
        <input type="text" name="kode" value="{{ $buku->kode }}" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Judul</label>
        <input type="text" name="judul" value="{{ $buku->judul }}" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Pengarang</label>
        <input type="text" name="pengarang" value="{{ $buku->pengarang }}" class="form-control" required>
    </div>

    <button type="button" id="btnSubmit" class="btn btn-warning me-2">Update</button>
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