<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Antrian #{{ $antrian->nomor_antrian }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <style>
        body {
            background: #f0f0f7;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .tiket {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            overflow: hidden;
            width: 360px;
        }
        .tiket-header {
            background: #4B49AC;
            color: white;
            padding: 24px;
            text-align: center;
        }
        .tiket-body { padding: 40px 32px; text-align: center; }
        .nomor { font-size: 100px; font-weight: 900; color: #4B49AC; line-height: 1; }
        .nama { font-size: 22px; font-weight: 600; color: #333; margin-top: 8px; }
        .tiket-footer {
            background: #f8f8ff;
            border-top: 2px dashed #ddd;
            padding: 16px;
            text-align: center;
            color: #888;
            font-size: 13px;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 12px;
        }
        .status-menunggu  { background: #fff3cd; color: #856404; }
        .status-dipanggil { background: #d4edda; color: #155724; animation: pulse 1s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.6} }
    </style>
</head>
<body>

<div class="tiket">
    <div class="tiket-header">
        <h4 class="mb-0">🏥 Nomor Antrian Anda</h4>
        <small>{{ now()->format('d/m/Y') }}</small>
    </div>
    <div class="tiket-body">
        <div class="nomor">{{ str_pad($antrian->nomor_antrian, 3, '0', STR_PAD_LEFT) }}</div>
        <div class="nama">{{ $antrian->nama }}</div>
        <div id="status_badge" class="status-badge status-menunggu">
            ⏳ Menunggu Dipanggil
        </div>
    </div>
    <div class="tiket-footer">
        Harap perhatikan papan antrian dan tetap berada di ruang tunggu.
    </div>
</div>

<script>
    const nomorAntrian = {{ $antrian->nomor_antrian }};
    const source = new EventSource('http://localhost:8001/sse/antrian');

    source.addEventListener('queue-update', function(e) {
        const data = JSON.parse(e.data);
        const badge = document.getElementById('status_badge');

        if (data.dipanggil && data.dipanggil.nomor_antrian == nomorAntrian) {
            badge.className = 'status-badge status-dipanggil';
            badge.textContent = '🔔 Anda Dipanggil! Silahkan Masuk.';
        } else {
            badge.className = 'status-badge status-menunggu';
            
            const pos = data.menunggu.findIndex(m => m.nomor_antrian == nomorAntrian);
            if (pos >= 0) {
                badge.textContent = `⏳ Posisi Antrian: ${pos + 1}`;
            }
        }
    });
</script>
</body>
</html>