@extends('layouts.app')

@section('title', 'Axios Wilayah')
@section('page-title', 'Axios Wilayah')
@section('icon', 'mdi mdi-map')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/home">Home</a></li>
    <li class="breadcrumb-item active">Axios Wilayah</li>
@endsection


@section('content')

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">

        <div class="card-header">
        <h4 class="card-title mb-0">Select Wilayah (AXIOS)</h4>
        </div>

        <div class="card-body">

            <div class="form-group">
                <label>Provinsi</label>
                <select id="provinsi" class="form-control">
                <option value="">-- pilih provinsi --</option>
                </select>
            </div>

            <div class="form-group">
                <label>Kota</label>
                <select id="kota" class="form-control">
                <option value="">-- pilih kota --</option>
                </select>
            </div>

            <div class="form-group">
                <label>Kecamatan</label>
                <select id="kecamatan" class="form-control">
                <option value="">-- pilih kecamatan --</option>
                </select>
            </div>

            <div class="form-group">
                <label>Kelurahan</label>
                <select id="kelurahan" class="form-control">
                <option value="">-- pilih kelurahan --</option>
                </select>
            </div>

        </div>
    </div>
    </div>
</div>

@endsection

@section('js-page')

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>

document.addEventListener("DOMContentLoaded", function(){

// load provinsi

axios.get("/api/provinsi")
.then(function(response){

    let data = response.data

    data.forEach(function(provinsi){

        document.getElementById("provinsi").innerHTML +=
        `<option value="${provinsi.id}">${provinsi.name}</option>`

    })

})



// load kota

document.getElementById("provinsi").addEventListener("change", function(){

    let idProvinsi = this.value

    document.getElementById("kota").innerHTML = '<option value="">-- pilih kota --</option>'
    document.getElementById("kecamatan").innerHTML = '<option value="">-- pilih kecamatan --</option>'
    document.getElementById("kelurahan").innerHTML = '<option value="">-- pilih kelurahan --</option>'

    axios.get("/api/kota/" + idProvinsi)
    .then(function(response){

        let data = response.data

        data.forEach(function(kota){

            document.getElementById("kota").innerHTML +=
            `<option value="${kota.id}">${kota.name}</option>`

        })

    })

})


// load kecamatan

document.getElementById("kota").addEventListener("change", function(){

    let idKota = this.value

    document.getElementById("kecamatan").innerHTML = '<option value="">-- pilih kecamatan --</option>'
    document.getElementById("kelurahan").innerHTML = '<option value="">-- pilih kelurahan --</option>'

    axios.get("/api/kecamatan/" + idKota)
    .then(function(response){

        let data = response.data

        data.forEach(function(kecamatan){

            document.getElementById("kecamatan").innerHTML +=
            `<option value="${kecamatan.id}">${kecamatan.name}</option>`

        })

    })

})


// load kelurahan

document.getElementById("kecamatan").addEventListener("change", function(){

    let idKecamatan = this.value

    document.getElementById("kelurahan").innerHTML = '<option value="">-- pilih kelurahan --</option>'

    axios.get("/api/kelurahan/" + idKecamatan)
    .then(function(response){

        let data = response.data

        data.forEach(function(kelurahan){

            document.getElementById("kelurahan").innerHTML +=
            `<option value="${kelurahan.id}">${kelurahan.name}</option>`

        })

    })

})

})

</script>

@endsection