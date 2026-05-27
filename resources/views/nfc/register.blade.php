@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftarkan Kartu NFC</h2>

    <div class="mb-3">
        <label>User</label>
        <select id="user_id" class="form-control">
            <option value="">-- Pilih User --</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">
                    {{ $user->name }} - {{ $user->email }}
                </option>
            @endforeach
        </select>
    </div>

    <button onclick="scanRegister()" class="btn btn-primary">
        Scan & Daftarkan Kartu
    </button>

    <p id="status" class="mt-3">Belum scan.</p>
    <div id="hasil" class="mt-3"></div>
</div>

<script>
async function scanRegister() {
    const userId = document.getElementById('user_id').value;
    const status = document.getElementById('status');
    const hasil = document.getElementById('hasil');

    if (!userId) {
        alert('Pilih user dulu.');
        return;
    }

    if (!('NDEFReader' in window)) {
        status.textContent = 'Browser tidak mendukung Web NFC.';
        return;
    }

    try {
        const ndef = new NDEFReader();
        await ndef.scan();

        status.textContent = 'NFC aktif. Dekatkan kartu...';

        ndef.addEventListener('reading', async ({ serialNumber }) => {
            status.textContent = 'Kartu terbaca. Mendaftarkan...';

            const response = await fetch("{{ route('nfc.register') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    user_id: userId,
                    serial_number: serialNumber,
                    card_uid: serialNumber
                })
            });

            const data = await response.json();

            hasil.innerHTML = `
                <div class="alert ${data.success ? 'alert-success' : 'alert-danger'}">
                    <p><b>Serial:</b> ${serialNumber}</p>
                    <p><b>Pesan:</b> ${data.message}</p>
                </div>
            `;

            status.textContent = data.message;
        });

    } catch (err) {
        status.textContent = 'Error: ' + err.message;
    }
}
</script>
@endsection