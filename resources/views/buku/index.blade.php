@extends('layouts.main')

@section('content')
<div class="page-header">
  <h3 class="page-title"> Kelola Buku </h3>
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
                <h4 class="card-title">Daftar Buku</h4>
                
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
                @endif

                <p class="card-description">
                    <a href="{{ route('buku.create') }}" class="btn btn-primary btn-sm">
                        <i class="mdi mdi-plus"></i> Tambah Buku
                    </a>
                </p>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Pengarang</th>
                                <th>Kategori</th> 
                                <th width="280px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($buku as $b)
                            <tr>
                                <td>{{ $b->kode }}</td>
                                <td>{{ $b->judul }}</td>
                                <td>{{ $b->pengarang }}</td>
                                <td>{{ $b->kategori->nama_kategori ?? 'Tidak Ada' }}</td>
                                <td>
                                    <form action="{{ route('buku.destroy', $b->idbuku) }}" method="POST">
                                        <a class="btn btn-warning btn-sm" href="{{ route('buku.edit', $b->idbuku) }}">
                                            <i class="mdi mdi-pencil"></i> Edit
                                        </a>

                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus buku ini?')">
                                            <i class="mdi mdi-delete"></i> Hapus
                                        </button>
                                    </form>
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