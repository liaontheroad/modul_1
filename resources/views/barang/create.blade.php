@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card shadow w-50 mx-auto">
        <div class="card-header bg-purple text-white">
            <h3 class="card-title mb-0">Tambah Permen Baru</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('barang.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label>Nama Permen (Inggris)</label>
                    <input type="text" name="nama" class="form-control" required placeholder="Contoh: Gummy Bear Candy">
                </div>
                
                <div class="form-group mb-4">
                    <label>Harga (Rupiah)</label>
                    <input type="number" name="harga" class="form-control" required placeholder="Contoh: 5000">
                </div>
                
                <button type="submit" class="btn btn-success">Simpan Data</button>
                <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection