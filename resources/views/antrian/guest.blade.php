<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Antrian</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <style>
        * { box-sizing: border-box; }
        body {
            background: linear-gradient(135deg, #4B49AC 0%, #7978E9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-size: 22px;
        }
        .card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 24px 80px rgba(0,0,0,0.25);
            width: 100%;
            max-width: 700px;
        }
        .card-header {
            background: #4B49AC;
            border-radius: 24px 24px 0 0 !important;
            padding: 40px 40px 36px;
            text-align: center;
        }
        .card-header h2 { font-size: 42px; font-weight: 800; margin-bottom: 10px; color: white; }
        .card-header p  { font-size: 20px; color: rgba(255,255,255,0.85); margin: 0; }
        .card-body      { padding: 48px 48px 52px; }
        label {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 12px;
            display: block;
        }
        .form-control {
            border-radius: 12px;
            padding: 20px 24px;
            font-size: 24px;
            border: 3px solid #ddd;
            height: auto;
            color: #222;
            transition: border-color 0.2s;
            margin-bottom: 24px;
        }
        .form-control:focus {
            border-color: #4B49AC;
            box-shadow: 0 0 0 4px rgba(75,73,172,0.15);
        }
        select.form-control { cursor: pointer; }
        .btn-daftar {
            background: #4B49AC;
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 28px;
            font-weight: 700;
            padding: 20px;
            width: 100%;
            margin-top: 8px;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }
        .btn-daftar:hover    { background: #3a3990; }
        .btn-daftar:active   { transform: scale(0.98); }
        .btn-daftar:disabled { background: #9998cc; cursor: not-allowed; }
        .error-text {
            color: #dc3545;
            font-size: 20px;
            margin-top: -16px;
            margin-bottom: 16px;
            display: none;
        }
        .hint-text {
            text-align: center;
            color: #888;
            font-size: 18px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">
        <h2>🏥 Sistem Antrian</h2>
        <p>Masukkan nama dan pilih poli untuk mendapatkan nomor antrian</p>
    </div>
    <div class="card-body">

        <label for="nama_input">Nama Lengkap</label>
        <input type="text" id="nama_input" class="form-control"
            placeholder="Contoh: Budi Santoso" autofocus autocomplete="off">
        <div class="error-text" id="nama_error">⚠️ Nama tidak boleh kosong.</div>

        <label for="ruangan_input">Pilih Poli / Ruangan</label>
        <select id="ruangan_input" class="form-control">
            <option value="">-- Pilih Poli --</option>
            <option value="Poli Umum">Poli Umum</option>
            <option value="Poli Gigi">Poli Gigi</option>
            <option value="Poli Mata">Poli Mata</option>
            <option value="Poli Anak">Poli Anak</option>
            <option value="Poli Kandungan">Poli Kandungan</option>
            <option value="Poli Jantung">Poli Jantung</option>
            <option value="Poli THT">Poli THT</option>
            <option value="Poli Kulit">Poli Kulit</option>
        </select>
        <div class="error-text" id="ruangan_error">⚠️ Pilih poli terlebih dahulu.</div>

        <button class="btn-daftar" onclick="daftar()" id="btn_daftar">
            🎫 Ambil Nomor Antrian
        </button>

        <p class="hint-text">Tekan tombol di atas atau tekan Enter</p>
    </div>
</div>

<script>
    async function daftar() {
        const nama    = document.getElementById('nama_input').value.trim();
        const ruangan = document.getElementById('ruangan_input').value;
        const errNama = document.getElementById('nama_error');
        const errRoom = document.getElementById('ruangan_error');
        const btn     = document.getElementById('btn_daftar');

        let valid = true;
        if (!nama) { errNama.style.display = 'block'; valid = false; } else { errNama.style.display = 'none'; }
        if (!ruangan) { errRoom.style.display = 'block'; valid = false; } else { errRoom.style.display = 'none'; }
        if (!valid) return;

        btn.disabled = true;
        btn.textContent = '⏳ Memproses...';

        try {
            const res = await fetch('/antrian/daftar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ nama, ruangan })
            });

            const data = await res.json();

            console.log('Response:', data);

            if (!data.id) {
                alert('Gagal mendapat nomor antrian. Coba lagi.');
                btn.disabled = false;
                btn.textContent = '🎫 Ambil Nomor Antrian';
                return;
            }

            window.open(`/antrian/tiket/${data.id}`, '_blank');

            document.getElementById('nama_input').value = '';
            document.getElementById('ruangan_input').value = '';
            btn.disabled = false;
            btn.textContent = '🎫 Ambil Nomor Antrian';

        } catch(err) {
            alert('Terjadi kesalahan. Silahkan coba lagi.');
            btn.disabled = false;
            btn.textContent = '🎫 Ambil Nomor Antrian';
        }
    }

    document.getElementById('nama_input').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') daftar();
    });
</script>
</body>
</html>