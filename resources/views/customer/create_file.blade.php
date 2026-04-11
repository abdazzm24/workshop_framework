@extends('layouts.app')

@section('content')

<h3>Tambah Customer (Upload)</h3>

<form method="POST" action="{{ route('customer.storeFile') }}" enctype="multipart/form-data">
@csrf

<input type="text" name="nama" placeholder="Nama" required>

<br><br>

<input type="file" name="foto" required>

<br><br>

<button type="submit">Upload</button>

</form>

@endsection