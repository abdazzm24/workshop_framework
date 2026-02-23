@extends('layouts.app')

@section('content')

<h3>Detail Buku</h3>

<table class="table table-bordered">
    <tr>
        <th>Kategori</th>
        <td>{{ $buku->kategori->nama_kategori }}</td>
    </tr>
    <tr>
        <th>Kode</th>
        <td>{{ $buku->kode }}</td>
    </tr>
    <tr>
        <th>Judul</th>
        <td>{{ $buku->judul }}</td>
    </tr>
    <tr>
        <th>Pengarang</th>
        <td>{{ $buku->pengarang }}</td>
    </tr>
</table>

<a href="{{ route('buku.index') }}" class="btn btn-secondary">Kembali</a>

@endsection