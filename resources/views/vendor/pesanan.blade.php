@extends('layouts.app')

@section('title', 'Data Pesanan')
@section('page-title', 'Data Pesanan')
@section('icon', 'mdi mdi-receipt')

@section('no-sidebar')
@endsection

@section('no-sidebar-class', 'full-page-wrapper')

@section('content')

<div class="mb-3">
    <a href="{{ route('vendor.index') }}" class="btn btn-secondary">
        ← Kembali ke Dashboard
    </a>
</div>

<h4 class="mb-3">Data Pesanan</h4>

<div class="mb-3">
    <a href="{{ route('vendor.pesanan') }}" class="btn btn-secondary btn-sm">
        Semua
    </a>

    <a href="{{ route('vendor.pesanan', ['status' => 'lunas']) }}"
       class="btn btn-success btn-sm">
        Lunas
    </a>
</div>

<div class="card">
<div class="card-body">

<div class="table-responsive">
<table class="table table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Status</th>
            <th>Detail</th>
        </tr>
    </thead>
    <tbody>

        @foreach($pesanan as $p)
        <tr>
            <td>#{{ $p->id_penjualan }}</td>
            <td>{{ $p->nama }}</td>

            <td>Rp {{ number_format($p->total) }}</td>

            <td>
                @if($p->status_bayar == 1)
                    <span class="badge bg-success">Lunas</span>
                @else
                    <span class="badge bg-warning">Pending</span>
                @endif
            </td>

            <td>
                {{-- nanti bisa detail --}}
                -
            </td>
        </tr>
        @endforeach

    </tbody>
</table>
</div>

</div>
</div>

@endsection