@extends('layouts.app')

@section('content')

<h3>Edit Barang</h3>

<form action="{{ route('barang.update', $barang->id_barang) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama"
               value="{{ $barang->nama }}"
               class="form-control">
    </div>

    <div class="form-group">
        <label>Harga</label>
        <input type="number" name="harga"
               value="{{ $barang->harga }}"
               class="form-control">
    </div>

    <button type="submit" class="btn btn-warning mt-2">Update</button>
    <a href="{{ route('barang.index') }}" class="btn btn-secondary mt-2">Kembali</a>

</form>

@endsection