<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NFC Scanner</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#f4f6f9;">

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow rounded-4 border-0">
                <div class="card-body p-5 text-center">

                    <h2 class="mb-4">NFC Scanner Absensi</h2>

                    <button id="scanBtn" onclick="startScan()" class="btn btn-primary btn-lg">
                        Aktifkan NFC
                    </button>

                    <button onclick="testServer()" class="btn btn-secondary mt-3">
                        Test Server
                    </button>

                    <p id="status" class="mt-4 text-muted">
                        Belum aktif.
                    </p>

                    <div id="hasil" class="mt-4"></div>

                </div>
            </div>

        </div>
    </div>

</div>

<script>
let isScanning = false;

async function startScan() {
    if (isScanning) return;

    const scanBtn = document.getElementById('scanBtn');
    const status = document.getElementById('status');
    const hasil = document.getElementById('hasil');

    isScanning = true;
    scanBtn.disabled = true;

    try {
        const ndef = new NDEFReader();
        await ndef.scan();

        status.innerHTML = `
            <div class="alert alert-success">
                NFC aktif. Dekatkan kartu...
            </div>
        `;

        ndef.onreading = async ({ serialNumber }) => {
            status.innerHTML = `
                <div class="alert alert-info">
                    Kartu terbaca: ${serialNumber}.<br>
                    Menyimpan absensi...
                </div>
            `;

            try {
                const response = await fetch("/nfc/scan", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        serial_number: serialNumber
                    })
                });

                const data = await response.json();

                hasil.innerHTML = `
                    <div class="alert ${data.success ? 'alert-success' : 'alert-danger'}">
                        <h5>${data.message}</h5>
                        <p><b>Serial NFC:</b> ${serialNumber}</p>
                        ${data.user ? `<p><b>Nama:</b> ${data.user.name}</p>` : ''}
                        ${data.user ? `<p><b>Email:</b> ${data.user.email}</p>` : ''}
                    </div>
                `;

            } catch (error) {
                hasil.innerHTML = `
                    <div class="alert alert-danger">
                        ERROR FETCH: ${error.message}
                    </div>
                `;
            }
        };

    } catch (err) {
        status.innerHTML = `
            <div class="alert alert-danger">
                Error: ${err.message}
            </div>
        `;

        scanBtn.disabled = false;
        isScanning = false;
    }
}

async function testServer() {
    try {
        const response = await fetch("/nfc/test");
        const data = await response.json();
        alert(data.message);
    } catch (e) {
        alert("Server test failed: " + e.message);
    }
}
</script>

</body>
</html>