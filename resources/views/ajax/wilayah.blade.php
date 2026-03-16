@extends('layouts.app')

@section('title', 'Ajax Wilayah')
@section('page-title', 'Ajax Wilayah')
@section('icon', 'mdi mdi-map')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/home">Home</a></li>
    <li class="breadcrumb-item active">Ajax Wilayah</li>
@endsection


@section('content')

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">

        <div class="card-header">
        <h4 class="card-title mb-0">Select Wilayah (AJAX)</h4>
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

<script>

$(document).ready(function(){

// load provinsi

$.ajax({

    url: "/api/provinsi",
    type: "GET",

    success: function(data){

        data.forEach(function(provinsi){

            $("#provinsi").append(
                `<option value="${provinsi.id}">${provinsi.name}</option>`
            )

        })

    }

})



// load kota

$("#provinsi").change(function(){

    let idProvinsi = $(this).val()

    $("#kota").html('<option value="">-- pilih kota --</option>')
    $("#kecamatan").html('<option value="">-- pilih kecamatan --</option>')
    $("#kelurahan").html('<option value="">-- pilih kelurahan --</option>')


    $.ajax({

        url: "/api/kota/"+idProvinsi,
        type: "GET",

        success: function(data){

            data.forEach(function(kota){

                $("#kota").append(
                    `<option value="${kota.id}">${kota.name}</option>`
                )

            })

        }

    })

})



// load kecamatan

$("#kota").change(function(){

    let idKota = $(this).val()

    $("#kecamatan").html('<option value="">-- pilih kecamatan --</option>')
    $("#kelurahan").html('<option value="">-- pilih kelurahan --</option>')


    $.ajax({

        url: "/api/kecamatan/"+idKota,
        type: "GET",

        success: function(data){

            data.forEach(function(kecamatan){

                $("#kecamatan").append(
                    `<option value="${kecamatan.id}">${kecamatan.name}</option>`
                )

            })

        }

    })

})



// load kelurahan

$("#kecamatan").change(function(){

    let idKecamatan = $(this).val()

    $("#kelurahan").html('<option value="">-- pilih kelurahan --</option>')


    $.ajax({

        url: "/api/kelurahan/"+idKecamatan,
        type: "GET",

        success: function(data){

            data.forEach(function(kelurahan){

                $("#kelurahan").append(
                    `<option value="${kelurahan.id}">${kelurahan.name}</option>`
                )

            })

        }

    })

})

})

</script>

@endsection