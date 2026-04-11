@extends('layouts.app')

@section('title', 'Detail Pesanan Vendor')

@section('content')

<div class="page-header">
    <h3 class="page-title">Detail Pesanan</h3>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('adminvendor.index') }}">Vendor</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('adminvendor.pesanan', $vendor->idvendor) }}">
                    {{ $vendor->nama_vendor }}
                </a>
            </li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
</div>

<div class="card">
<div class="card-body">

<p><strong>ID:</strong> {{ $pesanan->idpesanan }}</p>
<p><strong>Nama:</strong> {{ $pesanan->nama }}</p>
<p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($pesanan->tanggal)->format('d-m-Y H:i:s') }}</p>
<p><strong>Total:</strong> Rp {{ number_format($pesanan->total) }}</p>

<p>
    <strong>Status:</strong>
    <span class="badge {{ $pesanan->status_bayar ? 'bg-success' : 'bg-warning text-dark' }}">
        {{ $pesanan->status_bayar ? 'Lunas' : 'Pending' }}
    </span>
</p>

<div class="table-responsive">
<table class="table table-bordered">
<thead>
<tr>
    <th>Menu</th>
    <th>Harga</th>
    <th>Jumlah</th>
    <th>Subtotal</th>
</tr>
</thead>
<tbody>

@php $ada = false; @endphp

@foreach($pesanan->detailPesanan as $d)
    @if($d->menu && $d->menu->idvendor == $vendor->idvendor)
        @php $ada = true; @endphp
        <tr>
            <td>{{ $d->menu->nama_menu }}</td>
            <td>Rp {{ number_format($d->harga) }}</td>
            <td>{{ $d->jumlah }}</td>
            <td>Rp {{ number_format($d->subtotal) }}</td>
        </tr>
    @endif
@endforeach

@if(!$ada)
<tr>
    <td colspan="4" class="text-center">Tidak ada detail</td>
</tr>
@endif

</tbody>
</table>
</div>

<a href="{{ route('adminvendor.pesanan', $vendor->idvendor) }}"
   class="btn btn-secondary btn-sm mt-3">
    Kembali
</a>

</div>
</div>

@endsection