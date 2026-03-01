@extends('layouts.app')

@section('content')

<div class="page-header">
    <h3 class="page-title">Data Barang</h3>
    <div>
        <a href="{{ route('barang.create') }}" class="btn btn-success">
            + Tambah Barang
        </a>
    </div>
</div>

{{-- FORM CETAK LABEL --}}
<form action="{{ route('barang.cetak') }}" method="POST">
    @csrf

    <div style="margin-bottom:15px;">
        <label>Koordinat X :</label>
        <input type="number" name="x" min="1" max="6" required style="width:70px;">

        <label>Koordinat Y :</label>
        <input type="number" name="y" min="1" max="6" required style="width:70px;">

        <button type="submit" class="btn btn-primary btn-sm">
            Cetak Label
        </button>
    </div>

    <table class="table table-bordered" id="tableBarang">
        <thead>
            <tr>
                <th>Pilih</th>
                <th>ID</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Tanggal</th>
                <th>Aksi</th>
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
                <td>{{ $item->nama }}</td>
                <td>Rp {{ number_format($item->harga) }}</td>
                <td>{{ $item->timestamp }}</td>

                <td>
                    <a href="{{ route('barang.show', $item->id_barang) }}"
                       class="btn btn-info btn-sm">
                        <i class="mdi mdi-eye"></i>
                    </a>

                    <a href="{{ route('barang.edit', $item->id_barang) }}"
                       class="btn btn-warning btn-sm">
                        <i class="mdi mdi-pencil"></i>
                    </a>

                    {{-- FORM DELETE DIPINDAHKAN KE LUAR --}}
                    <button type="button"
                            class="btn btn-danger btn-sm"
                            onclick="deleteBarang({{ $item->id_barang }})">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>

{{-- FORM DELETE --}}
<form id="deleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

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