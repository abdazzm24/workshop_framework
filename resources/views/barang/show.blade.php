@extends('layouts.app')

@section('content')

<h3>Detail Barang</h3>

<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <td>{{ $barang->id_barang }}</td>
    </tr>
    <tr>
        <th>Nama</th>
        <td>{{ $barang->nama }}</td>
    </tr>
    <tr>
        <th>Harga</th>
        <td>Rp {{ number_format($barang->harga) }}</td>
    </tr>
    <tr>
        <th>Tanggal</th>
        <td>{{ $barang->timestamp }}</td>
    </tr>
</table>

<a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>

@endsection