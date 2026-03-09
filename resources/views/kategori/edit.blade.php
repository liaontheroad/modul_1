@extends('layouts.main')

@section('content')
<div class="page-header">
  <h3 class="page-title"> Edit Kategori </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori</a></li>
      <li class="breadcrumb-item active" aria-current="page">
        <span></span>Edit <i class="mdi mdi-pencil-circle-outline icon-sm text-primary align-middle"></i>
      </li>
    </ul>
  </nav>
</div>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Kategori</h4>
                <p class="card-description"> Ubah data kategori </p>
                
                <form id="formEditKategori" class="forms-sample" action="{{ route('kategori.update', $kategori->idkategori) }}" method="POST">
                    @csrf
                    @method('PUT') 
                    
                    <div class="form-group">
                        <label for="nama_kategori">Nama Kategori</label>
                        <input type="text" id="nama_kategori" class="form-control" name="nama_kategori" value="{{ $kategori->nama_kategori }}" required>
                    </div>
                    
                    <button type="button" id="btnUpdate" onclick="prosesUpdate()" class="btn btn-gradient-primary me-2">Update</button>
                    <a href="{{ route('kategori.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function prosesUpdate() {
        let form = document.getElementById("formEditKategori");
        let btn = document.getElementById("btnUpdate");
        if (!form.checkValidity()) {
            form.reportValidity();
        } else {
            btn.disabled = true; 
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengupdate...';
            form.submit();
        }
    }
</script>
@endsection