@extends('layouts.app')

@section('content')

<h3>Tambah Kategori</h3>

<form action="{{ route('kategori.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label>Nama Kategori</label>
        <input type="text" name="nama_kategori" class="form-control">
    </div>

    <button type="submit" class="btn btn-success mt-2">Simpan</button>
    <a href="{{ route('kategori.index') }}" class="btn btn-secondary mt-2">Kembali</a>

</form>

@endsection