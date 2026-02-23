@extends('layouts.main')

@section('content')
<div class="page-header">
  <h3 class="page-title"> Daftar Buku </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">
        <span></span>Buku <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
      </li>
    </ul>
  </nav>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">Koleksi Buku Perpustakaan</h4>
                    </div>
                
                <p class="card-description">
                    Berikut adalah daftar buku yang tersedia untuk dibaca di perpustakaan.
                </p>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Kode Buku</th>
                                <th>Judul Buku</th>
                                <th>Pengarang</th>
                                <th>Kategori</th>
                                </tr>
                        </thead>
                        <tbody>
                            @foreach ($buku as $b)
                            <tr>
                                <td>
                                    <label class="badge badge-info">{{ $b->kode_buku ?? $b->kode }}</label>
                                </td>
                                <td class="font-weight-bold">{{ $b->judul }}</td>
                                <td>{{ $b->pengarang }}</td>
                                <td>
                                    <label class="badge badge-outline-primary">
                                        {{ $b->kategori->nama_kategori ?? '-' }}
                                    </label>
                                </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($buku->isEmpty())
                    <div class="alert alert-warning mt-4">
                        Belum ada buku yang tersedia saat ini.
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection