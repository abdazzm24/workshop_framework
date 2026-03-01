@extends('layouts.app')

@section('content')

<h3>Tambah Barang</h3>

<form action="{{ route('barang.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control">
    </div>

    <div class="form-group">
        <label>Harga</label>
        <input type="number" name="harga" class="form-control">
    </div>

    <button type="submit" class="btn btn-success mt-2">Simpan</button>
    <a href="{{ route('barang.index') }}" class="btn btn-secondary mt-2">Kembali</a>

</form>

@endsection