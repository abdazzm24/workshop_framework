@extends('layouts.app')

@section('content')

<div class="page-header">
    <h3 class="page-title">Data Buku</h3>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Kategori</th>
            <th>Kode</th>
            <th>Judul</th>
            <th>Pengarang</th>
        </tr>
    </thead>
    <tbody>
        @foreach($buku as $item)
        <tr>
            <td>{{ $item->nama_kategori }}</td>
            <td>{{ $item->kode }}</td>
            <td>{{ $item->judul }}</td>
            <td>{{ $item->pengarang }}</td>
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