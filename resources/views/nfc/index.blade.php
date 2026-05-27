@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Data Absensi NFC</h2>

    <a href="{{ route('guest.nfc') }}" class="btn btn-primary mb-3">
        Scan Absensi
    </a>

    <a href="{{ route('nfc.register.page') }}" class="btn btn-secondary mb-3">
        Daftarkan Kartu
    </a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Serial NFC</th>
                <th>Tanggal</th>
                <th>Waktu Scan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($absensi as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td>{{ $item->user->email ?? '-' }}</td>
                    <td>{{ $item->nfcCard->serial_number ?? '-' }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->waktu_scan }}</td>
                    <td>{{ $item->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">
                        Belum ada data absensi.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection