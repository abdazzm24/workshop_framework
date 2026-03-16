@extends('layouts.app')

@section('title', 'Ajax Kasir')
@section('page-title', 'Ajax Kasir')
@section('icon', 'mdi mdi-cart')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/home">Home</a></li>
    <li class="breadcrumb-item active">Ajax Kasir</li>
@endsection



@section('content')

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">

        <div class="card">

            <div class="card-header">
                <h4 class="card-title mb-0">Kasir (AJAX)</h4>
            </div>

            <div class="card-body">

                <div class="form-group">
                    <label>Kode Barang</label>
                    <select id="kode_barang" class="form-control">
                        <option value="">-- pilih barang --</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Nama Barang</label>
                    <input type="text" id="nama_barang" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label>Harga Barang</label>
                    <input type="text" id="harga_barang" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" id="jumlah" class="form-control">
                </div>

                <br>

                <button id="btn_tambah" class="btn btn-success">
                    Tambahkan
                </button>

                <hr>


                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody id="table_detail">
                    </tbody>

                </table>


                <h4 class="mt-3">
                    Total : <span id="total">0</span>
                </h4>

                <br>

                <button id="btn_bayar" class="btn btn-success">
                    Bayar
                </button>

            </div>

        </div>

    </div>
</div>

<style>

.select2-container .select2-selection--single{
    height: 38px !important;
    display: flex !important;
    align-items: center !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered{
    line-height: normal !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow{
    height: 38px !important;
}

</style>

@endsection



@section('js-page')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$(document).ready(function(){

    // aktifkan select2
    $("#kode_barang").select2({
        placeholder:"Cari kode atau nama barang"
    })


    // ambil data barang dari database
    axios.get("/api/barang")

    .then(function(response){

        let data = response.data

        data.forEach(function(barang){

                $("#kode_barang").append(

                    `<option value="${barang.id_barang}"
                     data-nama="${barang.nama}"
                     data-harga="${barang.harga}">

                     ${barang.id_barang} - ${barang.nama}

                     </option>`

                )

            })

        })
    .catch(function(error){
        console.log(error)
    })


    // ketika barang dipilih

    $("#kode_barang").change(function(){

        let selected = $(this).find(':selected')

        let nama = selected.data("nama")

        let harga = selected.data("harga")

        $("#nama_barang").val(nama)

        $("#harga_barang").val(harga)

    })

    let total = 0
    let detail_penjualan = []

    $("#btn_tambah").click(function(){

        let kode = $("#kode_barang").val()
        let nama = $("#nama_barang").val()
        let harga = parseInt($("#harga_barang").val())
        let jumlah = parseInt($("#jumlah").val())

        if(!kode){
            alert("Pilih barang dulu")
            return
        }

        if(!jumlah || jumlah <= 0){
            alert("Jumlah harus diisi")
            return
        }

        let subtotal = harga * jumlah

        let row = `
            <tr>
                <td>${kode}</td>
                <td>${nama}</td>
                <td>${harga}</td>
                <td>${jumlah}</td>
                <td>${subtotal}</td>
            </tr>
        `

        $("#table_detail").append(row)

        total += subtotal
        $("#total").text(total)

        detail_penjualan.push({
            kode_barang: kode,
            harga: harga,
            jumlah: jumlah,
            subtotal: subtotal
        })

        console.log("ISI ARRAY:", detail_penjualan)

        $("#jumlah").val("")

    })

    $("#btn_bayar").click(function(){

        console.log("DATA DIKIRIM:", detail_penjualan)

        if(detail_penjualan.length == 0){
            Swal.fire({
                icon: "warning",
                title: "Belum ada barang"
            })
            return
        }

        Swal.fire({
            title: "Yakin ingin menyelesaikan transaksi?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yakin",
            cancelButtonText: "Batal"
        }).then((result) => {

            if(result.isConfirmed){

                Swal.fire({
                    title: "Memproses pembayaran...",
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                })

                axios.post("/kasir/bayar", {

                    _token : "{{ csrf_token() }}",
                    total : total,
                    detail : detail_penjualan

                })
                .then(function(res){

                    Swal.fire({
                        icon: "success",
                        title: "Pembayaran berhasil",
                        timer: 1500,
                        showConfirmButton: false
                    })

                    setTimeout(function(){
                        location.reload()
                    },1500)

                })
                .catch(function(err){

                    console.log(err)

                    Swal.fire({
                        icon: "error",
                        title: "Terjadi error"
                    })

                })

            }

        })

    })

})


</script>

@endsection


