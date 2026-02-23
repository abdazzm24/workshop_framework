@extends('layouts.app')

@section('content')

<h3>Edit Buku</h3>

<form action="{{ route('buku.update', $buku->idbuku) }}" method="POST">
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
        <input type="text" name="kode" value="{{ $buku->kode }}" class="form-control">
    </div>

    <div class="form-group">
        <label>Judul</label>
        <input type="text" name="judul" value="{{ $buku->judul }}" class="form-control">
    </div>

    <div class="form-group">
        <label>Pengarang</label>
        <input type="text" name="pengarang" value="{{ $buku->pengarang }}" class="form-control">
    </div>

    <button type="submit" class="btn btn-warning mt-2">Update</button>
    <a href="{{ route('buku.index') }}" class="btn btn-secondary mt-2">Kembali</a>

</form>

@endsection