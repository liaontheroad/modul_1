@extends('layouts.main')

@section('content')
<div class="page-header">
  <h3 class="page-title"> Kelola Kategori </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">
        <span></span>Kategori <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
      </li>
    </ul>
  </nav>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Kategori</h4>
                <p class="card-description">
                    Kategori buku yang tersedia di perpustakaan.
                </p>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kategori</th>
                                </tr>
                        </thead>
                        <tbody>
                            @foreach ($kategori as $k)
                            <tr>
                                <td>{{ $k->idkategori }}</td>
                                <td>
                                    <label class="badge badge-success">{{ $k->nama_kategori }}</label>
                                </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection