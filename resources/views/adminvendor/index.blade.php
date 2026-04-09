@extends('layouts.app')

@section('title', 'Data Vendor')
@section('page-title', 'Manajemen Vendor')
@section('icon', 'mdi mdi-store')

@section('content')

<div class="row">
<div class="col-lg-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">

<div class="d-flex justify-content-between align-items-center mb-3">

    <h4 class="card-title mb-0">Daftar Vendor</h4>

    <a href="{{ route('adminvendor.create') }}" class="btn btn-gradient-primary btn-sm">
        Tambah Vendor
    </a>
</div>

<div class="table-responsive">

<table class="table table-hover" id="tableVendor">
    <thead>
        <tr>
            <th>Nama Vendor</th>
            <th>Email</th>
            <th width="180">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($vendors as $v)
        <tr>
            <td>{{ $v->nama_vendor }}</td>
            <td>{{ $v->user->email ?? '-' }}</td> {{-- 🔥 relasi user --}}

            <td>
                <a href="{{ route('adminvendor.edit', $v->idvendor) }}"
                   class="btn btn-gradient-warning btn-sm">
                    <i class="mdi mdi-pencil"></i>
                </a>

                <button onclick="deleteVendor({{ $v->idvendor }})"
                        class="btn btn-gradient-danger btn-sm">
                    <i class="mdi mdi-delete"></i>
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>

<form id="deleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

</div>
</div>
</div>
</div>

@endsection

@section('js-page')
<script>
function deleteVendor(id) {
    if(confirm('Yakin hapus vendor?')) {
        let form = document.getElementById('deleteForm');
        form.action = '/vendor/' + id;
        form.submit();
    }
}
</script>
@endsection