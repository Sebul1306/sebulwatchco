<?= $this->extend("layout") ?>
<?= $this->section("content") ?>

<!-- jQuery & Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
/* Standardize height for both Bootstrap select and Select2 */
.select2-container .select2-selection--single {
    height: 38.6px !important;
    border: 1px solid #dee2e6 !important;
    border-radius: 6px !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 36.6px !important;
    color: #212529 !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36.6px !important;
}
.form-select {
    height: 38.6px !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
}
</style>

<div class="card">
  <div class="card-body pt-3">
    <h5 class="card-title">Cek Ongkos Kirim (Komerce API)</h5>
    <form id="cekOngkirForm">
      
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Lokasi Asal (Kelurahan)</label>
          <?php if(session()->get('role') == 'admin'): ?>
          <select class="form-select lokasi-select2" id="origin" name="origin" required>
            <option value="">Ketik Kelurahan Asal...</option>
          </select>
          <?php else: ?>
          <input type="hidden" name="origin" id="origin" value="<?= esc($store_address['id'] ?? '3273141004') ?>">
          <select class="form-select" disabled style="background-color: #f8f9fa; font-weight: 500;">
            <option selected><?= esc($store_address['name'] ?? 'Gegerkalong, Sukasari, Kota Bandung (Pusat)') ?></option>
          </select>
          <?php endif; ?>
        </div>
        <div class="col-md-6">
          <label class="form-label">Lokasi Tujuan (Kelurahan)</label>
          <select class="form-select lokasi-select2" id="destination" name="destination" required>
            <option value="">Ketik Kelurahan Tujuan...</option>
          </select>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Kurir</label>
          <select class="form-select" id="courier" name="courier" required>
            <option value="jne">JNE</option>
            <option value="pos">POS Indonesia</option>
            <option value="tiki">TIKI</option>
            <option value="sicepat">SiCepat</option>
            <option value="jnt">J&T Express</option>
            <option value="ninja">Ninja Xpress</option>
            <option value="ide">ID Express</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Berat (Gram)</label>
          <input type="number" class="form-control" id="weight" name="weight" value="1000" min="1" required>
        </div>
      </div>

      <button type="submit" class="btn btn-primary" id="btnCek">Cek Ongkir</button>
    </form>
    
    <div id="hasilOngkir" class="mt-4"></div>
  </div>
</div>

<script>
$(document).ready(function(){
    // Setup Select2 for Lokasi
    $('.lokasi-select2').select2({
        width: '100%',
        minimumInputLength: 3,
        language: {
            inputTooShort: function() { return 'Ketik minimal 3 huruf nama kelurahan...'; },
            noResults: function() { return 'Kelurahan tidak ditemukan'; },
            searching: function() { return 'Mencari...'; }
        },
        ajax: {
            url: '<?= base_url('ongkir/lokasi') ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { search: params.term };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return { id: item.id, text: item.label || item.name };
                    })
                };
            }
        }
    });

    // Form Submit
    $('#cekOngkirForm').submit(function(e){
        e.preventDefault();
        $('#btnCek').prop('disabled', true).text('Mengecek...');
        
        $.ajax({
            url: "<?= base_url('ongkir/biaya') ?>",
            type: "POST",
            data: $(this).serialize(),
            success: function(res){
                $('#btnCek').prop('disabled', false).text('Cek Ongkir');
                
                let hasil = $('#hasilOngkir');
                hasil.empty();

                if (res.data && res.data.length > 0) {
                    let costs = res.data[0].costs || res.data; // Komerce or Rajaongkir format
                    let table = `<table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Layanan</th>
                                            <th>Deskripsi</th>
                                            <th>Estimasi</th>
                                            <th>Tarif</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                    
                    $.each(costs, function(i, v){
                        let costValue = Array.isArray(v.cost) ? v.cost[0].value : v.cost;
                        let etdValue = Array.isArray(v.cost) ? v.cost[0].etd : (v.etd || v.estimasi || '-');
                        let service = v.service || v.name;
                        
                        table += `<tr>
                                    <td><strong>${service}</strong></td>
                                    <td>${v.description || '-'}</td>
                                    <td>${etdValue} Hari</td>
                                    <td>IDR ${parseInt(costValue).toLocaleString('id-ID')}</td>
                                  </tr>`;
                    });
                    table += `</tbody></table>`;
                    hasil.html(table);
                } else {
                    hasil.html('<div class="alert alert-warning">Tidak ada layanan / kurir yang tersedia untuk rute ini.</div>');
                }
            },
            error: function(){
                $('#btnCek').prop('disabled', false).text('Cek Ongkir');
                $('#hasilOngkir').html('<div class="alert alert-danger">Terjadi kesalahan pada server API.</div>');
            }
        });
    });
});
</script>

<?= $this->endSection() ?>
