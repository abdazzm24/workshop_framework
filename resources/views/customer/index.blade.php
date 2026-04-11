@extends('layouts.app')

@section('title', 'Data Customer')

@section('content')

<h3>Data Customer</h3>

<a href="{{ route('customer.create.blob') }}" class="btn btn-primary">Tambah (Blob)</a>
<a href="{{ route('customer.create.file') }}" class="btn btn-success">Tambah (File)</a>

<table class="table mt-3">
<tr>
    <th>Nama</th>
    <th>Foto</th>
</tr>

@foreach($customers as $c)
<tr>
    <td>{{ $c->nama }}</td>
    <td>
        @if($c->foto_blob)
            <img src="data:image/png;base64,{{ base64_encode($c->foto_blob) }}" width="80">
        @elseif($c->foto_path)
            <img src="{{ asset('storage/'.$c->foto_path) }}" width="80">
        @endif
    </td>
</tr>
@endforeach

</table>

@endsection