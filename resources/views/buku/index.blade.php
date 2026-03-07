@extends('layouts.app')

@section('title', 'Data Buku')
@section('page-title', 'Data Buku')
@section('icon', 'mdi mdi-book-open-variant')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Buku</li>
@endsection

@section('content')

<div class="row">
<div class="col-lg-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="card-title mb-0">Daftar Buku</h4>

    <div>
        <a href="{{ url('/buku/laporan/pdf') }}" class="btn btn-success btn-sm btn-download">
            <i class="mdi mdi-gradient-file-pdf"></i> Download PDF
        </a>

        <a href="{{ route('buku.create') }}" class="btn btn-gradient-primary btn-sm">
            <i class="mdi mdi-gradient-plus"></i> Tambah Buku
        </a>
    </div>
</div>

<div class="table-responsive">
<table class="table table-hover">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Judul</th>
            <th>Pengarang</th>
            <th>Kategori</th>
            <th width="180">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($buku as $item)
        <tr>
            <td><span class="badge badge-gradient-dark">{{ $item->kode }}</span></td>
            <td>{{ $item->judul }}</td>
            <td>{{ $item->pengarang }}</td>
            <td><label class="badge badge-gradient-info">{{ $item->kategori->nama_kategori }}</label></td>
            <td>
                {{-- SHOW --}}
                <a href="{{ route('buku.show', $item->idbuku) }}"
                    class="btn btn-gradient-info btn-sm">
                    <i class="mdi mdi-eye"></i>
                </a>

                {{-- EDIT --}}
                <a href="{{ route('buku.edit', $item->idbuku) }}"
                    class="btn btn-gradient-warning btn-sm">
                    <i class="mdi mdi-pencil"></i>
                </a>

                {{-- SERTIFIKAT --}}
                <a href="{{ url('/buku/'.$item->idbuku.'/sertifikat') }}"
                    class="btn btn-gradient-success btn-sm btn-download">
                    <i class="mdi mdi-download"></i>
                </a>

                {{-- HAPUS --}}
                <form action="{{ route('buku.destroy', $item->idbuku) }}"
                    method="POST"
                    style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn btn-gradient-danger btn-sm"
                            onclick="return confirm('Yakin ingin menghapus buku ini?')">
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
    console.log("Buku page loaded");
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