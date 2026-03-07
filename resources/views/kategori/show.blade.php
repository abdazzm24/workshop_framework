@extends('layouts.app')

@section('title', 'Detail Kategori')
@section('page-title', 'Detail Kategori')
@section('icon', 'mdi mdi-tag')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item"><a href="/kategori">Kategori</a></li>
<li class="breadcrumb-item active" aria-current="page">Detail</li>
@endsection

@section('content')

<div class="row">
<div class="col-md-12 grid-margin stretch-card">

<div class="card">
<div class="card-body">

<h4 class="card-title">Detail Kategori</h4>
<p class="card-description">Informasi lengkap kategori</p>

<table class="table table-bordered">
    <tr>
        <th>Nama Kategori</th>
        <td>{{ $kategori->nama_kategori }}</td>
    </tr>
</table>

<a href="{{ route('kategori.index') }}" class="btn btn-light mt-3">
    Kembali
</a>

</div>
</div>

</div>
</div>

@endsection