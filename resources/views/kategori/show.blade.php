@extends('layouts.app')

@section('content')

<h3>Detail Kategori</h3>

<table class="table table-bordered">
    <tr>
        <th>Nama Kategori</th>
        <td>{{ $kategori->nama_kategori }}</td>
    </tr>
</table>

<a href="{{ route('kategori.index') }}" class="btn btn-secondary">
    Kembali
</a>

@endsection