@extends('layouts.app')

@section('content')

<div class="page-header">
    <h3 class="page-title">Data Kategori</h3>
    <div>
        <a href="{{ url('/kategori/laporan/pdf') }}" class="btn btn-primary">
            Download Laporan Kategori (PDF)
        </a>

        <a href="{{ route('kategori.create') }}" class="btn btn-success">
            + Tambah Kategori
        </a>
    </div>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kategori</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($kategori as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->nama_kategori }}</td>
            <td>
                {{-- SHOW --}}
                <a href="{{ route('kategori.show', $item->idkategori) }}"
                    class="btn btn-info btn-sm">
                    <i class="mdi mdi-eye"></i>
                </a>

                {{-- EDIT --}}
                <a href="{{ route('kategori.edit', $item->idkategori) }}"
                    class="btn btn-warning btn-sm">
                    <i class="mdi mdi-pencil"></i>
                </a>

                {{-- HAPUS --}}
                <form action="{{ route('kategori.destroy', $item->idkategori) }}"
                      method="POST"
                      style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('js-page')
<script>
    console.log("Dashboard loaded");
</script>
@endsection