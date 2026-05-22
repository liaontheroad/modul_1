@extends('layouts.main')

@section('content')
<div class="page-header">
    <h3 class="page-title">Dashboard Antrian</h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
            <li class="breadcrumb-item active">Antrian</li>
        </ul>
    </nav>
</div>

{{-- ── SEDANG DILAYANI ─────────────────────────────────────── --}}
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body text-center py-4">
                <p class="text-muted mb-1">Sedang Dilayani</p>
                <h1 class="display-1 font-weight-bold text-primary mb-0" id="nomor_dipanggil">
                    {{ $dipanggil ? str_pad($dipanggil->nomor_antrian, 3, '0', STR_PAD_LEFT) : '---' }}
                </h1>
                <h4 class="text-dark mb-1" id="nama_dipanggil">
                    {{ $dipanggil ? $dipanggil->nama : 'Belum ada yang dipanggil' }}
                </h4>
                <span class="badge badge-success" id="ruangan_dipanggil" style="font-size:16px;">
                    {{ $dipanggil ? $dipanggil->ruangan : '' }}
                </span>
            </div>
        </div>
    </div>
</div>

{{-- ── ACTION BUTTONS ──────────────────────────────────────── --}}
<div class="row mb-3">
    <div class="col-lg-12 d-flex justify-content-between">
        <div>
            <button class="btn btn-success btn-lg mr-2" onclick="panggil()">
                <i class="mdi mdi-bell-ring"></i> Panggil Berikutnya
            </button>
            <button class="btn btn-warning btn-lg" onclick="tandaiTerlambat()">
                <i class="mdi mdi-clock-alert"></i> Tandai Terlambat
            </button>
        </div>
        <button class="btn btn-danger" onclick="resetAntrian()">
            <i class="mdi mdi-refresh"></i> Reset Antrian
        </button>
    </div>
</div>

{{-- ── DAFTAR ANTRIAN + TERLAMBAT ─────────────────────────── --}}
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    Antrian Menunggu
                    <span class="badge badge-primary ml-2" id="badge_total">{{ $menunggu->count() }}</span>
                </h4>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Poli / Ruangan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tabel_menunggu">
                            @forelse($menunggu as $a)
                            <tr data-id="{{ $a->id }}">
                                <td><label class="badge badge-info">{{ str_pad($a->nomor_antrian, 3, '0', STR_PAD_LEFT) }}</label></td>
                                <td class="font-weight-bold">{{ $a->nama }}</td>
                                <td><label class="badge badge-success">{{ $a->ruangan }}</label></td>
                                <td class="text-center">
                                    <button class="btn btn-warning btn-sm" onclick="tandaiTerlambatById({{ $a->id }})">
                                        <i class="mdi mdi-clock-alert"></i> Terlambat
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted">Tidak ada antrian.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    Daftar Terlambat
                    <span class="badge badge-warning ml-2" id="badge_terlambat">{{ $terlambat->count() }}</span>
                </h4>
                <p class="card-description">Double klik untuk memanggil ulang.</p>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Poli</th>
                            </tr>
                        </thead>
                        <tbody id="tabel_terlambat">
                            @forelse($terlambat as $a)
                            <tr data-id="{{ $a->id }}" ondblclick="panggilTerlambat({{ $a->id }})"
                                style="cursor:pointer;" title="Double klik untuk panggil ulang">
                                <td><label class="badge badge-warning">{{ str_pad($a->nomor_antrian, 3, '0', STR_PAD_LEFT) }}</label></td>
                                <td class="font-weight-bold">{{ $a->nama }}</td>
                                <td><small>{{ $a->ruangan }}</small></td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted">Tidak ada.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const csrfToken = '{{ csrf_token() }}';

    function playDingDong() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();

            const osc1  = ctx.createOscillator();
            const gain1 = ctx.createGain();
            osc1.connect(gain1);
            gain1.connect(ctx.destination);
            osc1.frequency.value = 880;
            osc1.type = 'sine';
            gain1.gain.setValueAtTime(1, ctx.currentTime);
            gain1.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.8);
            osc1.start(ctx.currentTime);
            osc1.stop(ctx.currentTime + 0.8);

            const osc2  = ctx.createOscillator();
            const gain2 = ctx.createGain();
            osc2.connect(gain2);
            gain2.connect(ctx.destination);
            osc2.frequency.value = 660;
            osc2.type = 'sine';
            gain2.gain.setValueAtTime(0, ctx.currentTime + 0.6);
            gain2.gain.setValueAtTime(1, ctx.currentTime + 0.7);
            gain2.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 1.5);
            osc2.start(ctx.currentTime + 0.6);
            osc2.stop(ctx.currentTime + 1.5);
        } catch(e) { console.error('DingDong error:', e); }
    }

    const source = new EventSource('http://localhost:8001/sse/antrian');
    source.addEventListener('queue-update', function(e) {
        const data = JSON.parse(e.data);

        if (data.dipanggil) {
            document.getElementById('nomor_dipanggil').textContent    = String(data.dipanggil.nomor_antrian).padStart(3, '0');
            document.getElementById('nama_dipanggil').textContent     = data.dipanggil.nama;
            document.getElementById('ruangan_dipanggil').textContent  = data.dipanggil.ruangan;
        } else {
            document.getElementById('nomor_dipanggil').textContent    = '---';
            document.getElementById('nama_dipanggil').textContent     = 'Belum ada yang dipanggil';
            document.getElementById('ruangan_dipanggil').textContent  = '';
        }

        document.getElementById('badge_total').textContent = data.total;
        const tbody = document.getElementById('tabel_menunggu');
        if (data.menunggu.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Tidak ada antrian.</td></tr>';
        } else {
            tbody.innerHTML = data.menunggu.map(a => `
                <tr data-id="${a.id}">
                    <td><label class="badge badge-info">${String(a.nomor_antrian).padStart(3,'0')}</label></td>
                    <td class="font-weight-bold">${a.nama}</td>
                    <td><label class="badge badge-success">${a.ruangan}</label></td>
                    <td class="text-center">
                        <button class="btn btn-warning btn-sm" onclick="tandaiTerlambatById(${a.id})">
                            <i class="mdi mdi-clock-alert"></i> Terlambat
                        </button>
                    </td>
                </tr>`).join('');
        }

        document.getElementById('badge_terlambat').textContent = data.terlambat.length;
        const tbodyT = document.getElementById('tabel_terlambat');
        if (data.terlambat.length === 0) {
            tbodyT.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Tidak ada.</td></tr>';
        } else {
            tbodyT.innerHTML = data.terlambat.map(a => `
                <tr data-id="${a.id}" ondblclick="panggilTerlambat(${a.id})"
                    style="cursor:pointer;" title="Double klik untuk panggil ulang">
                    <td><label class="badge badge-warning">${String(a.nomor_antrian).padStart(3,'0')}</label></td>
                    <td class="font-weight-bold">${a.nama}</td>
                    <td><small>${a.ruangan}</small></td>
                </tr>`).join('');
        }
    });

    function speak(text) {
        if (!('speechSynthesis' in window)) return;
        window.speechSynthesis.cancel();
        const utt = new SpeechSynthesisUtterance(text);
        utt.lang  = 'ja-JP';
        utt.rate  = 0.8;
        utt.pitch = 1.1;
        function doSpeak() {
            const voices    = window.speechSynthesis.getVoices();
            const preferred =
                voices.find(v => v.name.includes('Gadis'))  ||
                voices.find(v => v.name.includes('Andika')) ||
                voices.find(v => v.lang === 'id-ID')        ||
                voices.find(v => v.name.includes('Nanami')) ||
                voices.find(v => v.name.includes('Aria'));
            if (preferred) utt.voice = preferred;
            window.speechSynthesis.speak(utt);
        }
        if (window.speechSynthesis.getVoices().length > 0) {
            doSpeak();
        } else {
            window.speechSynthesis.onvoiceschanged = doSpeak;
        }
    }

    async function panggil() {
        const res = await fetch('/antrian/panggil', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' }
        });
        if (!res.ok) {
            const data = await res.json();
            alert(data.message || 'Tidak ada antrian.');
        } else {
            const data = await res.json();

            document.getElementById('nomor_dipanggil').textContent    = String(data.nomor_antrian).padStart(3, '0');
            document.getElementById('nama_dipanggil').textContent     = data.nama;
            document.getElementById('ruangan_dipanggil').textContent  = data.ruangan;

            const tbody    = document.getElementById('tabel_menunggu');
            const firstRow = tbody.querySelector('tr');
            if (firstRow) firstRow.remove();
            const badge = document.getElementById('badge_total');
            badge.textContent = Math.max(0, parseInt(badge.textContent) - 1);
            if (tbody.querySelectorAll('tr').length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Tidak ada antrian.</td></tr>';
            }

            playDingDong();
            setTimeout(() => {
                speak(`Nomor antrian ${data.nomor_antrian}, ${data.nama}, silahkan masuk ke ${data.ruangan}`);
            }, 1800);
        }
    }

    async function tandaiTerlambat() {
        const res = await fetch('/antrian/terlambat', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({})
        });
        if (!res.ok) {
            const data = await res.json();
            alert(data.message || 'Tidak ada yang sedang dipanggil.');
        }
    }

    async function tandaiTerlambatById(id) {
        await fetch('/antrian/terlambat', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({ id })
        });
    }

    async function panggilTerlambat(id) {
        const res = await fetch('/antrian/panggil-terlambat', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({ id })
        });
        if (res.ok) {
            const data = await res.json();
            document.getElementById('nomor_dipanggil').textContent    = String(data.nomor_antrian).padStart(3, '0');
            document.getElementById('nama_dipanggil').textContent     = data.nama;
            document.getElementById('ruangan_dipanggil').textContent  = data.ruangan;
            playDingDong();
            setTimeout(() => {
                speak(`Nomor antrian ${data.nomor_antrian}, ${data.nama}, silahkan masuk ke ${data.ruangan}`);
            }, 1800);
        }
    }

    async function resetAntrian() {
        if (!confirm('Reset semua antrian?')) return;
        await fetch('/antrian/reset', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' }
        });
    }
</script>
@endsection