@extends('layouts.app')

@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan')
@section('icon', 'mdi mdi-receipt')

@section('no-sidebar')
@endsection

@section('no-sidebar-class', 'full-page-wrapper')

@section('content')

<div class="mb-3">
    <a href="{{ route('vendor.pesanan') }}" class="btn btn-secondary">
        ← Kembali ke Pesanan
    </a>
</div>

<h4 class="mb-3">Detail Pesanan</h4>

<div class="card">
<div class="card-body">

    {{-- INFO PESANAN --}}
    <div class="mb-3">
        <p><strong>ID:</strong> #{{ $pesanan->idpesanan }}</p>
        <p><strong>Customer:</strong> {{ $pesanan->nama }}</p>
        <p><strong>Tanggal:</strong> 
            {{ \Carbon\Carbon::parse($pesanan->tanggal)->format('d-m-Y H:i') }}
        </p>
        <p><strong>Total:</strong> Rp {{ number_format($pesanan->total, 0, ',', '.') }}</p>

        <p>
            <strong>Status:</strong>
            @if($pesanan->status_bayar)
                <span class="badge bg-success">Lunas</span>
            @else
                <span class="badge bg-warning text-dark">Pending</span>
            @endif
        </p>
    </div>

    {{-- TABEL DETAIL --}}
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>

                @php $adaDetail = false; @endphp

                @foreach($pesanan->detailPesanan as $item)
                    @if($item->menu && $item->menu->vendor_id == auth()->user()->vendor->id)
                        @php $adaDetail = true; @endphp
                        <tr>
                            <td>{{ $item->menu->nama_menu }}</td>
                            <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                @endforeach

                @if(!$adaDetail)
                    <tr>
                        <td colspan="4" class="text-center">
                            Tidak ada detail pesanan untuk vendor ini
                        </td>
                    </tr>
                @endif

            </tbody>
        </table>
    </div>

    {{-- ACTION BUTTON --}}
    <div class="mt-3">

        {{-- STRUK --}}
        <a href="{{ route('vendor.pesanan.struk', $pesanan->idpesanan) }}" 
           target="_blank"
           class="badge bg-danger text-decoration-none">
            Cetak Struk
        </a>

        {{-- TANDAI LUNAS --}}
        @if(!$pesanan->status_bayar)
            <form action="{{ route('vendor.pesanan.lunas', $pesanan->idpesanan) }}" 
                  method="POST" 
                  class="d-inline">
                @csrf
                <button type="submit" class="badge bg-success border-0">
                    Tandai Lunas
                </button>
            </form>
        @endif

    </div>

</div>
</div>

@endsection