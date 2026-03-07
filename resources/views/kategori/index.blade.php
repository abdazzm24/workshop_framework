@extends('layouts.app')

@section('title', 'Data Kategori')
@section('page-title', 'Data Kategori')
@section('icon', 'mdi mdi-tag-multiple')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Kategori</li>
@endsection

@section('content')

<div class="row">
<div class="col-lg-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="card-title mb-0">Daftar Kategori</h4>

    <div>
        <a href="{{ url('/kategori/laporan/pdf') }}" class="btn btn-success btn-sm btn-download">
            <i class="mdi mdi-file-pdf"></i>Download PDF
        </a>

        <a href="{{ route('kategori.create') }}" class="btn btn-gradient-primary btn-sm">
            Tambah Kategori
        </a>
    </div>
</div>

<div class="table-responsive">
<table class="table table-hover">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kategori</th>
            <th width="180">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($kategori as $index => $item)
        <tr>
            <td><span class="badge badge-gradient-dark">{{ $index + 1 }}</span></td>
            <td>{{ $item->nama_kategori }}</td>
            <td>
                {{-- SHOW --}}
                <a href="{{ route('kategori.show', $item->idkategori) }}"
                    class="btn btn-gradient-info btn-sm">
                    <i class="mdi mdi-eye"></i>
                </a>

                {{-- EDIT --}}
                <a href="{{ route('kategori.edit', $item->idkategori) }}"
                    class="btn btn-gradient-warning btn-sm">
                    <i class="mdi mdi-pencil"></i>
                </a>

                {{-- HAPUS --}}
                <form action="{{ route('kategori.destroy', $item->idkategori) }}"
                      method="POST"
                      style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn btn-gradient-danger btn-sm"
                            onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

</div>
</div>
</div>
</div>

@endsection

@section('js-page')

<script>
    console.log("Dashboard loaded");
</script>

<script>

document.querySelectorAll(".btn-download").forEach(function(button){

    let originalContent = button.innerHTML;

    button.addEventListener("click", function(){

        this.innerHTML = `
        <span class="spinner-border spinner-border-sm"></span>
        `;

        this.classList.add("disabled");

        setTimeout(() => {
            this.innerHTML = originalContent;
            this.classList.remove("disabled");
        }, 2000);

    });

});

</script>

@endsection