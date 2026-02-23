@extends('layouts.app')

@section('content')

<div class="page-header">
    <h3 class="page-title">Data Buku</h3>
    <<div>
        <a href="{{ url('/buku/laporan/pdf') }}" class="btn btn-primary">
            Download Laporan Buku (PDF)
        </a>
        <a href="{{ route('buku.create') }}" class="btn btn-success">
            + Tambah Buku
        </a>
    </div>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Kategori</th>
            <th>Kode</th>
            <th>Judul</th>
            <th>Pengarang</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($buku as $item)
        <tr>
            <td>{{ $item->kategori->nama_kategori }}</td>
            <td>{{ $item->kode }}</td>
            <td>{{ $item->judul }}</td>
            <td>{{ $item->pengarang }}</td>
            <td>
                {{-- SHOW --}}
                <a href="{{ route('buku.show', $item->idbuku) }}"
                    class="btn btn-info btn-sm">
                    <i class="mdi mdi-eye"></i>
                </a>

                {{-- EDIT --}}
                <a href="{{ route('buku.edit', $item->idbuku) }}"
                    class="btn btn-warning btn-sm">
                    <i class="mdi mdi-pencil"></i>
                </a>

                {{-- SERTIFIKAT --}}
                <a href="{{ url('/buku/'.$item->idbuku.'/sertifikat') }}"
                    class="btn btn-success btn-sm">
                    <i class="mdi mdi-download"></i>
                </a>

                {{-- HAPUS --}}
                <form action="{{ route('buku.destroy', $item->idbuku) }}"
                    method="POST"
                    style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin ingin menghapus buku ini?')">
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
    console.log("Buku page loaded");
</script>
@endsection