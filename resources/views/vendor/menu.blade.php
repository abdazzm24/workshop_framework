@extends('layouts.app')

@section('title', 'Kelola Menu')
@section('page-title', 'Kelola Menu')
@section('icon', 'mdi mdi-food')

@section('no-sidebar')
@endsection

@section('no-sidebar-class', 'full-page-wrapper')

@section('content')

<div class="mb-3">
    <a href="{{ route('vendor.index') }}" class="btn btn-secondary">
        ← Kembali ke Dashboard
    </a>
</div>

<div class="card mb-4">
<div class="card-body">

<h5 class="mb-3">Tambah Menu</h5>

<form action="{{ route('vendor.menu.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Nama Menu</label>
        <input type="text" name="nama_menu" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Harga</label>
        <input type="number" name="harga" class="form-control" required>
    </div>

    <button class="btn btn-primary">Simpan</button>

</form>

</div>
</div>

<div class="card">
<div class="card-body">

<h5 class="mb-3">Daftar Menu</h5>

<div class="table-responsive">
<table class="table table-hover">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Harga</th>
            <th width="150">Aksi</th>
        </tr>
    </thead>
    <tbody>

        @foreach($menus as $m)
        <tr>
            <td>{{ $m->nama_menu }}</td>
            <td>Rp {{ number_format($m->harga) }}</td>

            <td>
                {{-- EDIT (opsional nanti) --}}
                <button class="btn btn-warning btn-sm" disabled>Edit</button>

                {{-- HAPUS --}}
                <form action="{{ route('vendor.menu.delete', $m->idmenu) }}"
                      method="POST"
                      style="display:inline;">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger btn-sm"
                        onclick="return confirm('Hapus menu?')">
                        Hapus
                    </button>
                </form>
            </td>
        </tr>
        @endforeach

    </tbody>
</table>
</div>

</div>
</div>

@endsection