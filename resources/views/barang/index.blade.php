@extends('layouts.app')

@section('title', 'Data Barang')
@section('page-title', 'Data Barang')
@section('icon', 'mdi mdi-book-open-variant')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Barang</li>
@endsection

@section('content')

<div class="row">
<div class="col-lg-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">

<div class="d-flex justify-content-between align-items-center mb-3">

    <h4 class="card-title mb-0">Daftar Barang</h4>

    <div>
        <a href="{{ route('barang.create') }}" class="btn btn-gradient-primary btn-sm">
            Tambah Barang
        </a>
    </div>
</div>

{{-- FORM CETAK LABEL --}}
<form action="{{ route('barang.cetak') }}" method="POST">
    @csrf

    <div class="mb-3">

        <label>Koordinat X :</label>
        <input type="number" name="x" min="1" max="8" required class="form-control d-inline-block mr-2" style="width:70px; height: 32px; opacity:0.8;">

        <label class="ml-2">Koordinat Y :</label>
        <input type="number" name="y" min="1" max="5" required class="form-control d-inline-block ml-2" style="width:70px; height: 32px; opacity:0.8;">

        <button type="submit" class="btn btn-gradient-primary btn-sm ml-2">
            Cetak Label
        </button>

    </div>

    <div class="table-responsive">

    <table class="table table-hover" id="tableBarang">
        <thead>
            <tr>
                <th>Pilih</th>
                <th>ID</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Tanggal</th>
                <th width="180">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barang as $item)
            <tr>
                <td>
                    <input type="checkbox"
                           name="barang_id[]"
                           value="{{ $item->id_barang }}">
                </td>

                <td>{{ $item->id_barang }}</td>
                <td><span class="badge badge-gradient-dark">{{ $item->nama }}</span></td>
                <td><label class="badge badge-gradient-info">Rp {{ number_format($item->harga) }}</label></td>
                <td>{{ $item->timestamp }}</td>

                <td>
                    <a href="{{ route('barang.show', $item->id_barang) }}"
                       class="btn btn-gradient-info btn-sm">
                        <i class="mdi mdi-eye"></i>
                    </a>

                    <a href="{{ route('barang.edit', $item->id_barang) }}"
                       class="btn btn-gradient-warning btn-sm">
                        <i class="mdi mdi-pencil"></i>
                    </a>

                    {{-- FORM DELETE DIPINDAHKAN KE LUAR --}}
                    <button type="button"
                            class="btn btn-gradient-danger btn-sm"
                            onclick="deleteBarang({{ $item->id_barang }})">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</form>

{{-- FORM DELETE --}}
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
function deleteBarang(id) {
    if(confirm('Yakin ingin menghapus barang ini?')) {
        let form = document.getElementById('deleteForm');
        form.action = '/barang/' + id;
        form.submit();
    }
}
</script>
<script>
    $(document).ready(function() {
        $('#tableBarang').DataTable({
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            ordering: true,
            searching: true,
            info: true
        });
    });
</script>

@endsection