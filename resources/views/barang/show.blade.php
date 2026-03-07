@extends('layouts.app')

@section('title', 'Detail Barang')
@section('page-title', 'Detail Barang')
@section('icon', 'mdi mdi-tag')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item"><a href="/barang">Barang</a></li>
<li class="breadcrumb-item active" aria-current="page">Detail</li>
@endsection

@section('content')

<div class="row">
<div class="col-md-12 grid-margin stretch-card">

<div class="card">
<div class="card-body">

<h4 class="card-title">Detail Barang</h4>
<p class="card-description">Informasi lengkap barang</p>

<table class="table table-bordered table-striped">
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

<a href="{{ route('barang.index') }}" class="btn btn-light mt-3">Kembali</a>

</div>
</div>

</div>
</div>

@endsection