@extends('layouts.app')

@section('content')

<h3>Edit Kategori</h3>

<form action="{{ route('kategori.update', $kategori->idkategori) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Nama Kategori</label>
        <input type="text"
               name="nama_kategori"
               value="{{ $kategori->nama_kategori }}"
               class="form-control">
    </div>

    <button type="submit" class="btn btn-warning mt-2">Update</button>
    <a href="{{ route('kategori.index') }}" class="btn btn-secondary mt-2">Kembali</a>

</form>

@endsection