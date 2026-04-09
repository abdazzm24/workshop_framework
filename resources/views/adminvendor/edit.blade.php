@extends('layouts.app')

@section('title', 'Edit Vendor')
@section('page-title', 'Edit Vendor')
@section('icon', 'mdi mdi-store-plus')

@section('content')

<div class="row">
<div class="col-md-6 mx-auto">
<div class="card">
<div class="card-body">

<h4 class="mb-4">Edit Nama Vendor</h4>

<form action="{{ route('adminvendor.update', $vendor->idvendor) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Nama Vendor</label>
        <input type="text" name="nama_vendor" class="form-control" value="{{ $vendor->nama_vendor }}" required>
    </div>

    <div class="mb-3">
        <label>Email User</label>

        {{-- tampilkan email (readonly) --}}
        <input type="text"
            class="form-control"
            value="{{ $vendor->user->email }}"
            readonly>

        {{-- tetap kirim user_id ke backend --}}
        <input type="hidden" name="user_id" value="{{ $vendor->user_id }}">
    </div>

    <button type="submit" class="btn btn-warning">Update</button>
    <a href="{{ route('adminvendor.index') }}" class="btn btn-light">Kembali</a>

</form>

</div>
</div>
</div>
</div>

@endsection