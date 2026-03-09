@extends('layouts.main')

@section('content')
<style>
    /* Styling Form Input (Select Biasa) */
    .form-control-custom {
        height: 45px !important;
        border: 1px solid #ebedf2 !important;
        background-color: #ffffff;
        border-radius: 4px;
        padding: 10px 15px;
        width: 100%;
    }

    /* Styling Search Input ala Navbar (Card Kanan) */
    .navbar-search-wrapper {
        background: #f1f3f9; /* Abu-abu terang ala navbar */
        border-radius: 4px;
        padding: 5px 15px;
        display: flex;
        align-items: center;
        height: 45px;
        border: 1px solid transparent;
    }
    .navbar-search-wrapper i {
        font-size: 1.1rem;
        color: #b66dff; /* Warna ungu icon */
        margin-right: 10px;
    }
    .navbar-search-wrapper input {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        width: 100%;
        color: #495057;
    }

    /* Box Hasil Terpilih */
    .result-box {
        background: #ffffff;
        border: 1px solid #ebedf2;
        border-radius: 4px;
        padding: 15px;
        min-height: 50px;
        display: flex;
        align-items: center;
        margin-top: 10px;
    }

    /* Dropdown Hasil Pencarian (Floating) */
    .search-dropdown {
        position: absolute;
        width: 90%;
        z-index: 1000;
        background: white;
        border: 1px solid #ebedf2;
        border-top: none;
        max-height: 150px;
        overflow-y: auto;
        display: none;
        box-shadow: 0px 4px 6px rgba(0,0,0,0.1);
    }
    .search-item {
        padding: 10px 15px;
        cursor: pointer;
    }
    .search-item:hover {
        background: #f1f3f9;
        color: #b66dff;
    }

    .card-header { font-weight: bold; font-size: 0.9rem; }
</style>

<div class="container-fluid mt-4">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h6 class="mb-3">1. Input Master Kota</h6>
            <div class="input-group">
                <input type="text" id="master_kota" class="form-control" placeholder="Tambahkan nama kota ke sistem..." style="height: 45px;">
                <div class="input-group-append">
                    <button class="btn btn-gradient-primary px-4" onclick="tambahkanKeSistem()">TAMBAH</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-gradient-primary text-white">SELECT BIASA</div>
                <div class="card-body">
                    <p class="text-muted small">Pilih dari List:</p>
                    <select id="select_biasa" class="form-control-custom" onchange="updateBiasa(this.value)">
                        <option value="">Pilih Kota...</option>
                    </select>
                    
                    <div class="mt-4">
                        <p class="text-muted small mb-1">Kota Terpilih!</p>
                        <div class="result-box">
                            <h6 id="hasil_biasa" class="mb-0 text-primary font-weight-bold">-</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-gradient-info text-white">SELECT 2 (NAVBAR SEARCH STYLE)</div>
                <div class="card-body">
                    <p class="text-muted small">Cari Kota:</p>
                    <div class="navbar-search-wrapper">
                        <i class="mdi mdi-magnify"></i>
                        <input type="text" id="nav_search" placeholder="Masukkan nama kota" autocomplete="off">
                    </div>
                    <div id="nav_results" class="search-dropdown"></div>

                    <div class="mt-4">
                        <p class="text-muted small mb-1">Kota Terpilih!</p>
                        <div class="result-box">
                            <h6 id="hasil_nav" class="mb-0 text-info font-weight-bold">-</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let listKota = [];

    function tambahkanKeSistem() {
        let val = $('#master_kota').val().trim();
        if (val === "") return;

        listKota.push(val);
        $('#select_biasa').append(new Option(val, val));
        
        $('#master_kota').val("").focus();
        alert('Kota ' + val + ' berhasil masuk daftar!');
    }

    function updateBiasa(val) {
        $('#hasil_biasa').text(val || "-");
    }
 
    $('#nav_search').on('keyup', function() {
        let query = $(this).val().toLowerCase();
        let resultsDiv = $('#nav_results');
        resultsDiv.empty();

        if (query.length > 0) {
            let filtered = listKota.filter(k => k.toLowerCase().includes(query));
            
            if (filtered.length > 0) {
                filtered.forEach(item => {
                    resultsDiv.append(`<div class="search-item" onclick="pilihNav('${item}')">${item}</div>`);
                });
                resultsDiv.show();
            } else {
                resultsDiv.hide();
            }
        } else {
            resultsDiv.hide();
        }
    });

    function pilihNav(kota) {
        $('#nav_search').val(kota);
        $('#hasil_nav').text(kota);
        $('#nav_results').hide();
    }

    $(document).click(function(e) {
        if (!$(e.target).closest('.navbar-search-wrapper, #nav_results').length) {
            $('#nav_results').hide();
        }
    });
</script>
@endsection