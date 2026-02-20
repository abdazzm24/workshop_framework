@extends('layouts.app')

@section('content')

<div class="page-header">
    <h3 class="page-title">Data Buku</h3>
    <a href="{{ url('/buku/laporan/pdf') }}" class="btn btn-primary">
        Download Laporan Buku (PDF)
    </a>
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
                <a href="{{ url('/buku/'.$item->idbuku.'/sertifikat') }}" 
                    class="btn btn-success btn-sm">
                    Sertifikat
                </a>
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