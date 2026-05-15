@extends('layouts.app')

@section('title', 'Kunjungan Toko')
@section('page-title', 'Kunjungan Toko')
@section('icon', 'mdi mdi-store')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item active">Kunjungan Toko</li>
@endsection

@section('content')

<div class="row">
<div class="col-lg-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="card-title mb-0">List Toko</h4>
        <div>
            <a href="{{ route('kunjungan.scan') }}" class="btn btn-gradient-success btn-sm me-2">
                <i class="mdi mdi-map-marker-radius"></i> Scan Kunjungan
            </a>
            <a href="{{ route('kunjungan.create') }}" class="btn btn-gradient-primary btn-sm">
                <i class="mdi mdi-plus"></i> Tambah Toko
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
    <table class="table table-hover" id="tableToko">
        <thead>
            <tr>
                <th>Barcode</th>
                <th>Nama Toko</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Accuracy</th>
                <th>Tanggal</th>
                <th width="150">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($toko as $item)
            <tr>
                <td><code>{{ $item->barcode }}</code></td>
                <td>{{ $item->nama_toko }}</td>
                <td>{{ $item->latitude }}</td>
                <td>{{ $item->longitude }}</td>
                <td>
                    <span class="badge bg-info text-white">
                        {{ $item->accuracy }} m
                    </span>
                </td>
                <td>{{ $item->created_at }}</td>
                <td>
                    {{-- Cetak Barcode --}}
                    <a href="{{ route('kunjungan.cetak', $item->barcode) }}"
                       target="_blank"
                       class="btn btn-gradient-info btn-sm"
                       title="Cetak Barcode">
                        <i class="mdi mdi-barcode"></i>
                    </a>

                    {{-- Hapus --}}
                    <button type="button"
                            class="btn btn-gradient-danger btn-sm"
                            onclick="hapusToko('{{ $item->barcode }}')"
                            title="Hapus">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>

</div>
</div>
</div>
</div>

{{-- Form delete tersembunyi --}}
<form id="deleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('js-page')
<script>
function hapusToko(barcode) {
    if (confirm('Yakin ingin menghapus toko ' + barcode + '?')) {
        let form = document.getElementById('deleteForm');
        form.action = '/kunjungan/' + barcode;
        form.submit();
    }
}

$(document).ready(function() {
    $('#tableToko').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25],
        ordering: true,
        searching: true,
    });
});
</script>
@endsection