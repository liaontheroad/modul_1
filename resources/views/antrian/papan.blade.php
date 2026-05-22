<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papan Antrian</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #0d0d2b;
            color: white;
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            overflow: hidden;
        }
        .papan-header {
            background: #4B49AC;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .papan-header h2 { font-size: 28px; font-weight: 700; }
        .jam { font-size: 32px; font-weight: 700; font-variant-numeric: tabular-nums; }
        .papan-main { display: flex; height: calc(100vh - 80px); }
        .panel-kiri {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #1a1a4e, #2d2d7e);
            padding: 40px;
        }
        .label-sekarang {
            font-size: 20px;
            color: #aaa;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .nomor-besar {
            font-size: 200px;
            font-weight: 900;
            color: #FFD700;
            line-height: 1;
            text-shadow: 0 0 40px rgba(255,215,0,0.4);
            transition: all 0.5s ease;
        }
        .nama-besar {
            font-size: 42px;
            font-weight: 600;
            color: white;
            margin-top: 10px;
            text-align: center;
        }
        .ruangan-besar {
            font-size: 28px;
            color: #7978E9;
            margin-top: 12px;
            padding: 8px 28px;
            border: 2px solid #7978E9;
            border-radius: 30px;
            letter-spacing: 2px;
        }
        .panel-kanan {
            width: 320px;
            background: #111133;
            padding: 24px;
            overflow-y: auto;
            border-left: 2px solid #2d2d7e;
        }
        .panel-kanan h5 {
            color: #aaa;
            letter-spacing: 3px;
            text-transform: uppercase;
            font-size: 13px;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #2d2d7e;
        }
        .antrian-item {
            display: flex;
            flex-direction: column;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 8px;
            background: #1a1a4e;
        }
        .antrian-item .top { display: flex; align-items: center; }
        .antrian-item .no  { font-size: 22px; font-weight: 800; color: #7978E9; width: 60px; }
        .antrian-item .nm  { font-size: 15px; color: #eee; }
        .antrian-item .rm  { font-size: 12px; color: #7978E9; margin-top: 4px; padding-left: 4px; }
        .antrian-item.active { background: #4B49AC; box-shadow: 0 0 20px rgba(75,73,172,0.5); }
        #overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.85);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 999;
            cursor: pointer;
        }
        #overlay h2 { color: white; font-size: 32px; margin-top: 20px; }
        #overlay p  { color: #aaa; font-size: 16px; margin-top: 10px; }
        @keyframes flash {
            0%,100% { background: linear-gradient(135deg, #1a1a4e, #2d2d7e); }
            50%      { background: linear-gradient(135deg, #4B49AC, #7978E9); }
        }
        .flashing { animation: flash 0.5s ease 3; }
    </style>
</head>
<body>

<div id="overlay" onclick="aktivasi()">
    <div style="font-size:60px;">🔔</div>
    <h2>Papan Antrian</h2>
    <p>Klik untuk mengaktifkan notifikasi suara</p>
</div>

<div class="papan-header">
    <h2>🏥 Sistem Antrian Digital</h2>
    <div class="jam" id="jam">00:00:00</div>
</div>

<div class="papan-main">
    <div class="panel-kiri" id="panel_kiri">
        <div class="label-sekarang">Nomor Dipanggil</div>
        <div class="nomor-besar" id="nomor_besar">---</div>
        <div class="nama-besar" id="nama_besar">Menunggu...</div>
        <div class="ruangan-besar" id="ruangan_besar"></div>
    </div>

    <div class="panel-kanan">
        <h5>Antrian Menunggu</h5>
        <div id="list_menunggu">
            <div class="text-muted text-center small mt-4">Belum ada antrian</div>
        </div>
    </div>
</div>

<script>
    let aktivasi_done = false;
    let nomorTerakhir = null;

    function aktivasi() {
        aktivasi_done = true;
        document.getElementById('overlay').style.display = 'none';
        speak('Sistem antrian siap digunakan');
    }

    function updateJam() {
        document.getElementById('jam').textContent =
            new Date().toLocaleTimeString('id-ID', { hour12: false });
    }
    setInterval(updateJam, 1000);
    updateJam();

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
        } catch(e) {}
    }

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

    const source = new EventSource('http://localhost:8001/sse/antrian');

    source.addEventListener('queue-update', function(e) {
        const data = JSON.parse(e.data);

        if (data.dipanggil) {
            const nomor   = String(data.dipanggil.nomor_antrian).padStart(3, '0');
            const nama    = data.dipanggil.nama;
            const ruangan = data.dipanggil.ruangan;

            document.getElementById('nomor_besar').textContent   = nomor;
            document.getElementById('nama_besar').textContent    = nama;
            document.getElementById('ruangan_besar').textContent = ruangan;

            if (data.dipanggil.nomor_antrian !== nomorTerakhir) {
                nomorTerakhir = data.dipanggil.nomor_antrian;

                if (aktivasi_done) {
                    const panel = document.getElementById('panel_kiri');
                    panel.classList.remove('flashing');
                    void panel.offsetWidth;
                    panel.classList.add('flashing');

                    playDingDong();
                    setTimeout(() => {
                        speak(`Nomor antrian ${data.dipanggil.nomor_antrian}, ${nama}, silahkan masuk ke ${ruangan}`);
                    }, 1800);
                }
            }
        } else {
            document.getElementById('nomor_besar').textContent   = '---';
            document.getElementById('nama_besar').textContent    = 'Menunggu...';
            document.getElementById('ruangan_besar').textContent = '';
        }

        const listEl = document.getElementById('list_menunggu');
        if (data.menunggu.length === 0) {
            listEl.innerHTML = '<div class="text-muted text-center small mt-4">Belum ada antrian</div>';
        } else {
            listEl.innerHTML = data.menunggu.map((a, i) => `
                <div class="antrian-item ${i === 0 ? 'active' : ''}">
                    <div class="top">
                        <div class="no">${String(a.nomor_antrian).padStart(3,'0')}</div>
                        <div class="nm">${a.nama}</div>
                    </div>
                    <div class="rm">📍 ${a.ruangan}</div>
                </div>`).join('');
        }
    });

    source.onerror = function() {
        console.warn('SSE reconnecting...');
    };
</script>
</body>
</html>