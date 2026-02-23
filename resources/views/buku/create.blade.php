@extends('layouts.app')

@section('content')

<h3>Tambah Buku</h3>

<form action="{{ route('buku.store') }}" method="POST">
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
        <input type="text" name="kode" class="form-control">
    </div>

    <div class="form-group">
        <label>Judul</label>
        <input type="text" name="judul" class="form-control">
    </div>

    <div class="form-group">
        <label>Pengarang</label>
        <input type="text" name="pengarang" class="form-control">
    </div>

    <button type="submit" class="btn btn-success mt-2">Simpan</button>
    <a href="{{ route('buku.index') }}" class="btn btn-secondary mt-2">Kembali</a>

</form>

@endsection