@extends('layouts.main')

@section('content')
<div class="page-header">
  <h3 class="page-title"> Edit Buku </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('buku.index') }}">Buku</a></li>
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
                <h4 class="card-title">Edit Buku</h4>
                
                <form id="formEditBuku" class="forms-sample" action="{{ route('buku.update', $buku->idbuku) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label>Kode Buku</label>
                        <input type="text" class="form-control" name="kode" value="{{ $buku->kode }}" required>
                    </div>

                    <div class="form-group">
                        <label>Judul Buku</label>
                        <input type="text" class="form-control" name="judul" value="{{ $buku->judul }}" required>
                    </div>

                    <div class="form-group">
                        <label>Pengarang</label>
                        <input type="text" class="form-control" name="pengarang" value="{{ $buku->pengarang }}" required>
                    </div>

                    <div class="form-group">
                        <label>Kategori</label>
                        <select class="form-control" name="idkategori" required>
                            @foreach($kategori as $k)
                                <option value="{{ $k->idkategori }}" {{ $buku->idkategori == $k->idkategori ? 'selected' : '' }}>
                                    {{ $k->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="button" id="btnUpdateBuku" onclick="prosesUpdateBuku()" class="btn btn-gradient-primary me-2">Update</button>
                    <a href="{{ route('buku.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function prosesUpdateBuku() {
        let form = document.getElementById("formEditBuku");
        let btn = document.getElementById("btnUpdateBuku");
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