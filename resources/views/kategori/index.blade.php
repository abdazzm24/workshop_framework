@extends('layouts.app')

@section('content')

<div class="page-header">
    <h3 class="page-title">Data Kategori</h3>
    <a href="{{ url('/kategori/laporan/pdf') }}" class="btn btn-primary">
        Download Laporan Kategori (PDF)
    </a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kategori</th>
        </tr>
    </thead>
    <tbody>
        @foreach($kategori as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->nama_kategori }}</td>
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