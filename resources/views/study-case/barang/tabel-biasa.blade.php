@extends('layouts.main')

@section('content')
<style>
    #tabelBiasa tbody tr {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    #tabelBiasa tbody tr:hover {
        background-color: #f1f1f1;
    }
</style>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Form Input</h4>
                </div>
                <div class="card-body">
                    <form id="formSimulasi">
                        <div class="form-group mb-3">
                            <label>Nama Barang:</label>
                            <input type="text" id="nama_barang" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Harga Barang:</label>
                            <input type="number" id="harga_barang" class="form-control" required>
                        </div>
                        <button type="button" id="btnSubmit" class="btn btn-success w-100" onclick="prosesSimpan()">Submit</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Tabel HTML Biasa (Klik Baris untuk Edit/Hapus)</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered text-center" id="tabelBiasa">
                        <thead>
                            <tr>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga Barang</th>
                            </tr>
                        </thead>
                        <tbody>
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAksi" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title text-dark" id="modalLabel">Kelola Data Barang</h5>
      </div>
      <div class="modal-body">
        <form id="formModal">
            <div class="form-group mb-3">
                <label>ID Barang :</label>
                <input type="text" id="modal_id" class="form-control bg-light" readonly>
            </div>
            <div class="form-group mb-3">
                <label>Nama Barang :</label>
                <input type="text" id="modal_nama" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label>Harga Barang :</label>
                <input type="number" id="modal_harga" class="form-control" required>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnUbah" class="btn btn-primary" onclick="prosesUbah()">Ubah</button>
        <button type="button" id="btnHapus" class="btn btn-danger" onclick="prosesHapus()">Hapus</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let counterId = 1;
    let barisTerpilih = null; 

    function prosesSimpan() {
        let form = document.getElementById("formSimulasi");
        let btn = document.getElementById("btnSubmit");
        let nama = document.getElementById("nama_barang");
        let harga = document.getElementById("harga_barang");

        if (!form.checkValidity()) {
            form.reportValidity();
        } else {
            let originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';

            setTimeout(function() {
                let tbody = document.querySelector("#tabelBiasa tbody");
                let newRow = `<tr onclick="bukaModal(this)">
                                <td class="kolom-id">ID-${counterId}</td>
                                <td class="kolom-nama">${nama.value}</td>
                                <td class="kolom-harga" data-harga="${harga.value}">Rp ${harga.value}</td>
                              </tr>`;
                tbody.insertAdjacentHTML('beforeend', newRow);
                counterId++;

                nama.value = "";
                harga.value = "";
                btn.disabled = false;
                btn.innerHTML = originalText;
            }, 500);
        }
    }

    function bukaModal(row) {
        barisTerpilih = row; 

        let idVal = row.querySelector('.kolom-id').innerText;
        let namaVal = row.querySelector('.kolom-nama').innerText;
        let hargaVal = row.querySelector('.kolom-harga').getAttribute('data-harga');

        document.getElementById('modal_id').value = idVal;
        document.getElementById('modal_nama').value = namaVal;
        document.getElementById('modal_harga').value = hargaVal;

        $('#modalAksi').modal('show');
    }

    function prosesUbah() {
        let form = document.getElementById("formModal");
        let btn = document.getElementById("btnUbah");
        let namaBaru = document.getElementById("modal_nama").value;
        let hargaBaru = document.getElementById("modal_harga").value;

        if (!form.checkValidity()) {
            form.reportValidity();
        } else {
            let originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';

            setTimeout(function() {
                barisTerpilih.querySelector('.kolom-nama').innerText = namaBaru;
                barisTerpilih.querySelector('.kolom-harga').innerText = "Rp " + hargaBaru;
                barisTerpilih.querySelector('.kolom-harga').setAttribute('data-harga', hargaBaru);

                btn.disabled = false;
                btn.innerHTML = originalText;
                
                $('#modalAksi').modal('hide');
            }, 800);
        }
    }

    function prosesHapus() {
        let btn = document.getElementById("btnHapus");
        let originalText = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menghapus...';

        setTimeout(function() {
            barisTerpilih.remove();
            
            btn.disabled = false;
            btn.innerHTML = originalText;

            $('#modalAksi').modal('hide');
        }, 800);
    }
</script>
@endsection