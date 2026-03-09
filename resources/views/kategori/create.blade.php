@extends('layouts.main')

@section('content')
<div class="page-header">
  <h3 class="page-title"> Tambah Kategori </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori</a></li>
      <li class="breadcrumb-item active" aria-current="page">
        <span></span>Tambah <i class="mdi mdi-plus-circle-outline icon-sm text-primary align-middle"></i>
      </li>
    </ul>
  </nav>
</div>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Kategori</h4>
                <p class="card-description"> Masukkan kategori baru </p>
                
                <form id="formTambahKategori" class="forms-sample" action="{{ route('kategori.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="nama_kategori">Nama Kategori</label>
                        <input type="text" id="nama_kategori" class="form-control" name="nama_kategori" placeholder="Contoh: Novel" required>
                    </div>
                    
                    <button type="button" id="btnSubmit" onclick="prosesSubmit()" class="btn btn-gradient-primary me-2">Submit</button>
                    <a href="{{ route('kategori.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function prosesSubmit() {
        let form = document.getElementById("formTambahKategori");
        let btn = document.getElementById("btnSubmit");
        if (!form.checkValidity()) {
            form.reportValidity();
        } else {
            btn.disabled = true; 
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
            form.submit();
        }
    }
</script>
@endsection