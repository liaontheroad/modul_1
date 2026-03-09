@extends('layouts.main')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

<style>
    #tabelDataTables tbody tr {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    #tabelDataTables tbody tr:hover {
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
                    <form id="formDataTables">
                        <div class="form-group mb-3">
                            <label>Nama Barang:</label>
                            <input type="text" id="dt_nama" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Harga Barang:</label>
                            <input type="number" id="dt_harga" class="form-control" required>
                        </div>
                        <button type="button" id="btnSubmitDT" class="btn btn-success w-100" onclick="prosesSimpanDT()">Submit</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Tabel DataTables (Klik Baris untuk Edit/Hapus)</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped text-center" id="tabelDataTables">
                        <thead>
                            <tr>
                                <th>ID Barang</th>
                                <th>Nama</th>
                                <th>Harga</th>
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

<div class="modal fade" id="modalAksiDT" tabindex="-1" aria-labelledby="modalLabelDT" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title text-dark" id="modalLabelDT">Kelola Data Barang</h5>
      </div>
      <div class="modal-body">
        <form id="formModalDT">
            <div class="form-group mb-3">
                <label>ID Barang :</label>
                <input type="text" id="modal_id_dt" class="form-control bg-light" readonly>
            </div>
            <div class="form-group mb-3">
                <label>Nama Barang :</label>
                <input type="text" id="modal_nama_dt" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label>Harga Barang :</label>
                <input type="number" id="modal_harga_dt" class="form-control" required>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnUbahDT" class="btn btn-primary" onclick="prosesUbahDT()">Ubah</button>
        <button type="button" id="btnHapusDT" class="btn btn-danger" onclick="prosesHapusDT()">Hapus</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    var counterIdDT = 1;
    var barisTerpilihDT = null; 

    $(document).ready(function() {
        try {
            $('#tabelDataTables').DataTable();
        } catch (e) {
            console.log("DataTables bentrok, pakai backup mode.");
        }

        $('#tabelDataTables tbody').on('click', 'tr', function () {
            if ($(this).find('.dataTables_empty').length > 0) return; 

            barisTerpilihDT = this;
            let idVal = $(this).find('td:eq(0)').text();
            let namaVal = $(this).find('td:eq(1)').text();
            let hargaVal = $(this).find('td:eq(2)').text().replace('Rp ', '').replace(/\./g, '');

            $('#modal_id_dt').val(idVal);
            $('#modal_nama_dt').val(namaVal);
            $('#modal_harga_dt').val(hargaVal);

            $('#modalAksiDT').modal('show');
        });
    });

    function prosesSimpanDT() {
        let form = document.getElementById("formDataTables");
        let btn = document.getElementById("btnSubmitDT");
        let nama = document.getElementById("dt_nama");
        let harga = document.getElementById("dt_harga");

        if (!form.checkValidity()) {
            form.reportValidity();
        } else {
            let originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';

            setTimeout(function() {
                try {
                    $('#tabelDataTables').DataTable().row.add([
                        'DT-' + counterIdDT,
                        nama.value,
                        'Rp ' + harga.value
                    ]).draw(false);
                } catch (e) {
                    let tbody = document.querySelector("#tabelDataTables tbody");
                    let newRow = `<tr>
                                    <td>DT-${counterIdDT}</td>
                                    <td>${nama.value}</td>
                                    <td>Rp ${harga.value}</td>
                                  </tr>`;
                    tbody.insertAdjacentHTML('beforeend', newRow);
                }

                counterIdDT++;
                nama.value = "";
                harga.value = "";

                btn.disabled = false;
                btn.innerHTML = originalText;
            }, 800);
        }
    }

   
    function prosesUbahDT() {
        let form = document.getElementById("formModalDT");
        let btn = document.getElementById("btnUbahDT");
        
        if (!form.checkValidity()) {
            form.reportValidity();
        } else {
            let originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';

            setTimeout(function() {
                let idLama = $('#modal_id_dt').val();
                let namaBaru = $('#modal_nama_dt').val();
                let hargaBaru = $('#modal_harga_dt').val();

                try {
                    
                    $('#tabelDataTables').DataTable().row(barisTerpilihDT).data([
                        idLama, 
                        namaBaru, 
                        'Rp ' + hargaBaru
                    ]).draw(false);
                } catch (e) {
                    
                    $(barisTerpilihDT).find('td:eq(1)').text(namaBaru);
                    $(barisTerpilihDT).find('td:eq(2)').text('Rp ' + hargaBaru);
                }

                btn.disabled = false;
                btn.innerHTML = originalText;
                $('#modalAksiDT').modal('hide'); 
            }, 800);
        }
    }

    function prosesHapusDT() {
        let btn = document.getElementById("btnHapusDT");
        let originalText = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menghapus...';

        setTimeout(function() {
            try {
                $('#tabelDataTables').DataTable().row(barisTerpilihDT).remove().draw(false);
            } catch (e) {
                
                $(barisTerpilihDT).remove();
            }

            btn.disabled = false;
            btn.innerHTML = originalText;
            $('#modalAksiDT').modal('hide'); 
        }, 800);
    }
</script>
@endsection