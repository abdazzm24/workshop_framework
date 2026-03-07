@extends('layouts.app')

@section('title', 'Detail Buku')
@section('page-title', 'Detail Buku')
@section('icon', 'mdi mdi-book')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item"><a href="/buku">Buku</a></li>
<li class="breadcrumb-item active" aria-current="page">Detail</li>
@endsection

@section('content')

<div class="row">
<div class="col-md-12 grid-margin stretch-card">

<div class="card">
<div class="card-body">

<h4 class="card-title">Detail Buku</h4>
<p class="card-description">Informasi lengkap buku</p>

<table class="table table-bordered table-striped">
    <tr>
        <th width="200">Kategori</th>
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

<a href="{{ route('buku.index') }}" class="btn btn-light mt-3">Kembali</a>

</div>
</div>

</div>
</div>

@endsection