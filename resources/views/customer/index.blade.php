@extends('layouts.app')

@section('title', 'Data Customer')
@section('page-title', 'Data Customer')
@section('icon', 'mdi mdi-account-group')

@section('content')

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Daftar Customer</h4>
            <div>
                <a href="{{ route('customer.createBlob') }}" class="btn btn-primary me-2">
                    <i class="mdi mdi-camera"></i> Tambah Customer 1 (Blob)
                </a>
                <a href="{{ route('customer.createFile') }}" class="btn btn-success">
                    <i class="mdi mdi-file-image"></i> Tambah Customer 2 (File)
                </a>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Kota</th>
                                <th>Tipe Foto</th>
                                <th>Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $c)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($c->foto_blob)
                                        <img src="{{ $c->foto_blob }}"
                                            style="width:50px; height:50px; object-fit:cover; border-radius:8px;">
                                    @elseif($c->foto_path)
                                        <img src="{{ asset('storage/' . $c->foto_path) }}"
                                            style="width:50px; height:50px; object-fit:cover; border-radius:8px;">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $c->nama }}</td>
                                <td>{{ $c->alamat ?? '-' }}</td>
                                <td>{{ $c->kota ?? '-' }}</td>
                                <td>
                                    @if($c->foto_blob)
                                        <span class="badge bg-primary">Blob</span>
                                    @elseif($c->foto_path)
                                        <span class="badge bg-success">File</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>{{ $c->created_at ? $c->created_at->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada data customer
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection