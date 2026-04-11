@extends('layouts.app')

@section('title', 'Pesanan Vendor')

@section('content')
<div class="page-header">
    <h3 class="page-title">Pesanan Vendor</h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('adminvendor.index') }}">Data Vendor</a>
            </li>
            <li class="breadcrumb-item active">
                {{ $vendor->nama_vendor }}
            </li>
        </ul>
    </nav>
</div>

<div class="mb-3">
    <a href="{{ route('adminvendor.index') }}" class="btn btn-light btn-sm">
        Kembali
    </a>
</div>

<div class="card">
<div class="card-body">

<h4 class="card-title">Pesanan - {{ $vendor->nama_vendor }}</h4>

@if($pesanan->count())

<div class="table-responsive">
<table class="table table-bordered">
    <thead>
        <tr>
            <th width="80">ID</th>
            <th>Nama Pembeli</th>
            <th>Waktu</th>
            <th>Total</th>
            <th>Status</th>
            <th width="120">Aksi</th>
        </tr>
    </thead>
    <tbody>

        @foreach($pesanan as $item)
        <tr>
            <td>#{{ $item->idpesanan }}</td>

            <td>{{ $item->nama }}</td>

            <td>
                {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y H:i') }}
            </td>

            <td>
                Rp {{ number_format($item->total, 0, ',', '.') }}
            </td>

            <td>
                <span class="badge {{ $item->status_bayar ? 'bg-success' : 'bg-warning text-dark' }}">
                    {{ $item->status_bayar ? 'Lunas' : 'Pending' }}
                </span>
            </td>

            <td>
                <a href="{{ route('adminvendor.pesanan.detail', [$vendor->idvendor, $item->idpesanan]) }}"
                   class="btn btn-primary btn-sm">
                    Detail
                </a>
            </td>
        </tr>
        @endforeach

    </tbody>
</table>
</div>

@else
<div class="alert alert-warning">
    Belum ada pesanan.
</div>
@endif

</div>
</div>
@endsection