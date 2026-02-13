@extends('layouts.main')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Kategori</h4>
                <p class="card-description"> Masukkan kategori baru </p>
                
                <form class="forms-sample" action="{{ route('kategori.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="nama_kategori">Nama Kategori</label>
                        <input type="text" class="form-control" name="nama_kategori" placeholder="Contoh: Novel" required>
                    </div>
                    
                    <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                    <a href="{{ route('kategori.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection