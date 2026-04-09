@extends('layouts.app')

@section('title', 'Buat Vendor')
@section('page-title', 'Buat Vendor')
@section('icon', 'mdi mdi-store-plus')

@section('content')

<div class="row">
<div class="col-md-6 mx-auto">
<div class="card">
<div class="card-body">

<h4 class="mb-4">Isi Nama Vendor</h4>

<form action="{{ route('adminvendor.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Nama Vendor</label>
        <input type="text" name="nama_vendor" class="form-control" required>
    </div>

    {{-- 🔥 PILIH USER --}}
    <div class="mb-3">
        <label>Email User</label>
        <select name="user_id" class="form-control" required>
            <option value="">-- Pilih User --</option>

            @foreach($users as $u)
                <option value="{{ $u->id }}">
                    {{ $u->email }}
                </option>
            @endforeach
        </select>
    </div>

    <button class="btn btn-primary">
        Simpan
    </button>

</form>

</div>
</div>
</div>
</div>

@endsection