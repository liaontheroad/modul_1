@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card shadow w-50 mx-auto">
        <div class="card-header bg-purple text-white">
            <h3 class="card-title mb-0">Edit Permen</h3>
        </div>
        <div class="card-body">
            
            <form id="formEditPermen" action="{{ route('barang.update', $barang->id_barang) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group mb-3">
                    <label>Nama Permen (Inggris)</label>
                    <input type="text" name="nama" class="form-control" value="{{ $barang->nama }}" required>
                </div>
                
                <div class="form-group mb-4">
                    <label>Harga (Rupiah)</label>
                    <input type="number" name="harga" class="form-control" value="{{ $barang->harga }}" required>
                </div>
                
                <button type="button" id="btnUpdatePermen" onclick="prosesUpdatePermen()" class="btn btn-success">Update Data</button>
                <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>

<script>
    function prosesUpdatePermen() {
        let form = document.getElementById("formEditPermen");
        let btn = document.getElementById("btnUpdatePermen");
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