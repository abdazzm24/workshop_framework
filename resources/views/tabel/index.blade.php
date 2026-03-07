@extends('layouts.app')

@section('title', 'Tabel Barang')
@section('page-title', 'Tabel Barang')
@section('icon', 'mdi mdi-table')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/home">Home</a></li>
    <li class="breadcrumb-item active">Tabel</li>
@endsection

@section('content')

<div class="row">
<div class="col-lg-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">

    <h4 class="card-title">Input Barang</h4>

    <div class="form-group">
        <label>Nama Barang</label>
        <input type="text" id="nama" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Harga Barang</label>
        <input type="number" id="harga" class="form-control" required>
    </div>

    <button id="btnSubmit" class="btn btn-success mt-2">submit
    </button>

    <br><br>

    <div class="table-responsive">

    <style>
        #tableBarang tbody tr{
        cursor: pointer;
        }
    </style>
    <table class="table table-bordered" id="tableBarang">
        <thead>
            <tr>
                <th>ID barang</th>
                <th>Nama</th>
                <th>Harga</th>
            </tr>
        </thead>

        <tbody>

        </tbody>

    </table>

    <div class="modal fade" id="modalEdit">
    <div class="modal-dialog">
    <div class="modal-content">

    <div class="modal-header">
        <h5 class="modal-title">Edit Barang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body">

    <div class="mb-3">
        <label>ID Barang</label>
        <input type="text" id="editId" class="form-control" readonly>
    </div>

    <div class="mb-3">
        <label>Nama Barang</label>
        <input type="text" id="editNama" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Harga Barang</label>
        <input type="number" id="editHarga" class="form-control" required>
    </div>

    </div>

    <div class="modal-footer">
        <button class="btn btn-danger" id="btnDelete">Hapus</button>
        <button class="btn btn-primary" id="btnUpdate">Ubah</button>
    </div>

    </div>
    </div>
    </div>

    </div>

</div>
</div>
</div>
</div>

@endsection

@section('js-page')

<script>

let idBarang = 1;

document.getElementById("btnSubmit").addEventListener("click", function(){

    let nama = document.getElementById("nama");
    let harga = document.getElementById("harga");

    if(nama.value === "" || harga.value === ""){
        alert("Nama dan Harga harus diisi");
        return;
    }

    let table = document.getElementById("tableBarang").getElementsByTagName('tbody')[0];

    let row = table.insertRow();

    row.insertCell(0).innerHTML = idBarang++;
    row.insertCell(1).innerHTML = nama.value;
    row.insertCell(2).innerHTML = "Rp " + harga.value;

    nama.value = "";
    harga.value = "";

});

</script>

<script>

let selectedRow = null;
let modal = new bootstrap.Modal(document.getElementById('modalEdit'));

// click row
document.querySelector("#tableBarang tbody").addEventListener("click", function(e){

let row = e.target.closest("tr");

if(!row) return;

selectedRow = row;

let id = row.cells[0].innerText;
let nama = row.cells[1].innerText;
let harga = row.cells[2].innerText.replace("Rp ","");

document.getElementById("editId").value = id;
document.getElementById("editNama").value = nama;
document.getElementById("editHarga").value = harga;

modal.show();

});

//update data
document.getElementById("btnUpdate").addEventListener("click", function(){

let nama = document.getElementById("editNama").value;
let harga = document.getElementById("editHarga").value;

if(nama=="" || harga==""){
alert("Nama dan harga wajib diisi");
return;
}

selectedRow.cells[1].innerText = nama;
selectedRow.cells[2].innerText = "Rp " + harga;

modal.hide();

});

// delete data
document.getElementById("btnDelete").addEventListener("click", function(){

selectedRow.remove();

modal.hide();

});

</script>

@endsection