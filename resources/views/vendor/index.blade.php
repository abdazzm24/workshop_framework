@extends('layouts.app')

@section('title', 'Vendor Dashboard')
@section('page-title', 'Dashboard Vendor')
@section('icon', 'mdi mdi-store')

@section('no-sidebar')
@endsection

@section('no-sidebar-class', 'full-page-wrapper')

@section('content')

<div class="row">
    <div class="col-md-4 grid-margin">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body">
                <h4>Total Produk</h4>
                <h2>10</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 grid-margin">
        <div class="card bg-gradient-success text-white">
            <div class="card-body">
                <h4>Total Transaksi</h4>
                <h2>5</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 grid-margin">
        <div class="card bg-gradient-info text-white">
            <div class="card-body">
                <h4>Penghasilan</h4>
                <h2>Rp 1.000.000</h2>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body text-center">
        <h4>Selamat datang, {{ auth()->user()->name }}</h4>
        <p>Ini adalah halaman vendor</p>
    </div>
</div>

{{-- 🔥 BARU: ACTION BUTTON --}}
<div class="row mt-4">

    <div class="col-md-6 mb-3">
        <a href="{{ route('vendor.menu') }}" class="text-decoration-none">
            <div class="card bg-gradient-warning text-white text-center p-4">
                <i class="mdi mdi-food mdi-48px"></i>
                <h5 class="mt-3">Kelola Menu</h5>
            </div>
        </a>
    </div>

    <div class="col-md-6 mb-3">
        <a href="{{ route('vendor.pesanan') }}" class="text-decoration-none">
            <div class="card bg-gradient-danger text-white text-center p-4">
                <i class="mdi mdi-receipt mdi-48px"></i>
                <h5 class="mt-3">Lihat Pesanan</h5>
            </div>
        </a>
    </div>
    
    <div class="col-md-6 mb-3">
        <a href="{{ route('vendor.scan.qr') }}" class="text-decoration-none">
            <div class="card bg-gradient-danger text-white text-center p-4">
                <i class="mdi mdi-qrcode-scan mdi-48px"></i>
                <h5 class="mt-3">Scan QR Customer</h5>
            </div>
        </a>
    </div>

</div>

@endsection